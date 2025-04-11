<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CardController extends Controller
{
    public function index()
    {
        $cards = Card::with('profile')
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => Card::count(),
            'active' => Card::where('status', 'active')->count(),
            'inactive' => Card::where('status', 'inactive')->count(),
            'lost' => Card::where('status', 'lost')->count(),
            'expiring_soon' => Card::where('status', 'active')
                ->where('expires_at', '<=', now()->addMonth())
                ->count()
        ];

        return view('cards.index', compact('cards', 'stats'));
    }

    public function create()
    {
        $profiles = Profile::whereDoesntHave('card')
            ->where('Status', true)
            ->get();

        return view('cards.create', compact('profiles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'profile_id' => 'required|exists:profiles,id',
            'lunch_in' => 'required|date_format:H:i:s',
            'lunch_out' => 'required|date_format:H:i:s|after:lunch_in',
            'access_permissions' => 'nullable|array'
        ]);

        try {
            DB::beginTransaction();

            // Check if profile already has a card
            if (Card::where('profile_id', $request->profile_id)->exists()) {
                return back()->with('error', 'Profile already has a card assigned');
            }

            $card = Card::create([
                'profile_id' => $request->profile_id,
                'card_number' => $this->generateUniqueCardNumber(),
                'status' => 'active',
                'lunch_in' => $request->lunch_in,
                'lunch_out' => $request->lunch_out,
                'issued_at' => now(),
                'expires_at' => now()->addYear(),
                'access_permissions' => json_encode($request->access_permissions ?? [
                    'can_access_library' => true,
                    'can_access_lab' => true,
                    'can_access_gym' => true
                ]),
                'meta_data' => json_encode([
                    'issuer' => auth()->user()->name,
                    'batch' => Carbon::now()->format('Y-m'),
                    'notes' => 'Regular student card'
                ])
            ]);

            DB::commit();

            return redirect()->route('cards.show', $card)
                ->with('success', 'Card created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while creating the card');
        }
    }

    public function show(Card $card)
    {
        $card->load('profile');
        
        // Get card usage statistics
        $stats = [
            'total_lunches' => $card->lunches()->count(),
            'total_days_used' => $card->lunches()
                ->select(DB::raw('COUNT(DISTINCT DATE(date)) as days'))
                ->first()
                ->days,
            'avg_lunch_duration' => $card->lunches()
                ->whereNotNull('lunch_out')
                ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, lunch_in, lunch_out)) as avg_duration'))
                ->first()
                ->avg_duration
        ];

        // Get recent activity
        $recentActivity = $card->lunches()
            ->with('profile')
            ->latest()
            ->take(10)
            ->get();

        return view('cards.show', compact('card', 'stats', 'recentActivity'));
    }

    public function edit(Card $card)
    {
        $card->load('profile');
        return view('cards.edit', compact('card'));
    }

    public function update(Request $request, Card $card)
    {
        $request->validate([
            'lunch_in' => 'required|date_format:H:i:s',
            'lunch_out' => 'required|date_format:H:i:s|after:lunch_in',
            'status' => 'required|in:active,inactive,lost',
            'access_permissions' => 'nullable|array'
        ]);

        try {
            DB::beginTransaction();

            $card->update([
                'lunch_in' => $request->lunch_in,
                'lunch_out' => $request->lunch_out,
                'status' => $request->status,
                'access_permissions' => json_encode($request->access_permissions ?? $card->access_permissions),
                'meta_data' => json_encode(array_merge(
                    json_decode($card->meta_data, true) ?? [],
                    [
                        'last_updated_by' => auth()->user()->name,
                        'last_updated_at' => now()->toDateTimeString()
                    ]
                ))
            ]);

            if ($request->status === 'lost') {
                // Create a new card with the same permissions but new number
                $newCard = $card->replicate();
                $newCard->card_number = $this->generateUniqueCardNumber();
                $newCard->status = 'active';
                $newCard->issued_at = now();
                $newCard->expires_at = now()->addYear();
                $newCard->meta_data = json_encode([
                    'issuer' => auth()->user()->name,
                    'batch' => Carbon::now()->format('Y-m'),
                    'notes' => 'Replacement card for lost card #' . $card->card_number
                ]);
                $newCard->save();

                DB::commit();
                return redirect()->route('cards.show', $newCard)
                    ->with('success', 'Card marked as lost and replacement card created');
            }

            DB::commit();
            return redirect()->route('cards.show', $card)
                ->with('success', 'Card updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while updating the card');
        }
    }

    public function destroy(Card $card)
    {
        try {
            DB::beginTransaction();

            $card->update([
                'status' => 'inactive',
                'meta_data' => json_encode(array_merge(
                    json_decode($card->meta_data, true) ?? [],
                    [
                        'deactivated_by' => auth()->user()->name,
                        'deactivated_at' => now()->toDateTimeString(),
                        'reason' => 'Card deleted by administrator'
                    ]
                ))
            ]);

            $card->delete();

            DB::commit();
            return redirect()->route('cards.index')
                ->with('success', 'Card deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while deleting the card');
        }
    }

    public function report(Request $request)
    {
        $query = Card::with('profile');
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Expiry filter
        if ($request->filled('expiry')) {
            switch ($request->expiry) {
                case 'expired':
                    $query->where('expires_at', '<', now());
                    break;
                case 'expiring_soon':
                    $query->whereBetween('expires_at', [now(), now()->addMonth()]);
                    break;
                case 'valid':
                    $query->where('expires_at', '>', now());
                    break;
            }
        }

        $cards = $query->latest()->paginate(15);

        // Get statistics
        $stats = [
            'total_active' => Card::where('status', 'active')->count(),
            'total_inactive' => Card::where('status', 'inactive')->count(),
            'total_lost' => Card::where('status', 'lost')->count(),
            'expired' => Card::where('expires_at', '<', now())->count(),
            'expiring_soon' => Card::whereBetween('expires_at', [now(), now()->addMonth()])->count()
        ];

        // Get usage statistics
        $usageStats = DB::table('lunches')
            ->join('cards', 'lunches.card_id', '=', 'cards.id')
            ->select(
                DB::raw('COUNT(*) as total_uses'),
                DB::raw('COUNT(DISTINCT lunches.card_id) as unique_cards'),
                DB::raw('AVG(CASE WHEN lunches.lunch_out IS NOT NULL THEN TIMESTAMPDIFF(MINUTE, lunches.lunch_in, lunches.lunch_out) END) as avg_duration')
            )
            ->first();

        return view('cards.report', compact('cards', 'stats', 'usageStats'));
    }

    private function generateUniqueCardNumber()
    {
        do {
            $number = strtoupper(Str::random(8));
        } while (Card::where('card_number', $number)->exists());

        return $number;
    }
}
