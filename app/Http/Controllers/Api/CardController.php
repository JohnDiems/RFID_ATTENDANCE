<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CardController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'profile_id' => 'required|exists:profiles,id',
                'lunch_in' => 'required|date_format:H:i:s',
                'lunch_out' => 'required|date_format:H:i:s|after:lunch_in',
                'access_permissions' => 'nullable|array'
            ]);

            DB::beginTransaction();

            // Check if profile already has a card
            if (Card::where('profile_id', $request->profile_id)->exists()) {
                return response()->json(['error' => 'Profile already has a card assigned'], 400);
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

            return response()->json([
                'message' => 'Card created successfully',
                'data' => $card
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating card: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while creating the card'], 500);
        }
    }

    public function update(Request $request, Card $card)
    {
        try {
            $request->validate([
                'lunch_in' => 'required|date_format:H:i:s',
                'lunch_out' => 'required|date_format:H:i:s|after:lunch_in',
                'status' => 'required|in:active,inactive,lost',
                'access_permissions' => 'nullable|array'
            ]);

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
            }

            DB::commit();

            return response()->json([
                'message' => 'Card updated successfully',
                'data' => [
                    'card' => $card,
                    'new_card' => $newCard ?? null
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating card: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while updating the card'], 500);
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

            return response()->json([
                'message' => 'Card deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting card: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the card'], 500);
        }
    }

    private function generateUniqueCardNumber()
    {
        do {
            $number = strtoupper(Str::random(8));
        } while (Card::where('card_number', $number)->exists());

        return $number;
    }
}
