<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::latest()->paginate(15);

        $activeSchedule = Schedule::where('Status', true)
            ->where('EventTimeIn', '<=', now()->format('H:i:s'))
            ->where('EventTimeout', '>=', now()->format('H:i:s'))
            ->first();

        $stats = [
            'total' => Schedule::count(),
            'active' => Schedule::where('Status', true)->count(),
            'inactive' => Schedule::where('Status', false)->count()
        ];

        return view('schedules.index', compact('schedules', 'activeSchedule', 'stats'));
    }

    public function create()
    {
        return view('schedules.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'EventTimeIn' => 'required|date_format:H:i:s',
            'EventTimeout' => 'required|date_format:H:i:s|after:EventTimeIn',
            'Description' => 'required|string',
            'Status' => 'required|boolean'
        ]);

        try {
            DB::beginTransaction();

            // If this schedule is being set as active, deactivate all other schedules
            if ($request->Status) {
                Schedule::where('Status', true)->update(['Status' => false]);
            }

            Schedule::create([
                'EventTimeIn' => $request->EventTimeIn,
                'EventTimeout' => $request->EventTimeout,
                'Description' => $request->Description,
                'Status' => $request->Status,
                'meta_data' => json_encode([
                    'created_by' => auth()->user()->name,
                    'created_at' => now()->toDateTimeString(),
                    'notes' => $request->notes ?? null
                ])
            ]);

            DB::commit();

            return redirect()->route('schedules.index')
                ->with('success', 'Schedule created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while creating the schedule');
        }
    }

    public function edit(Schedule $schedule)
    {
        return view('schedules.edit', compact('schedule'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'EventTimeIn' => 'required|date_format:H:i:s',
            'EventTimeout' => 'required|date_format:H:i:s|after:EventTimeIn',
            'Description' => 'required|string',
            'Status' => 'required|boolean'
        ]);

        try {
            DB::beginTransaction();

            // If this schedule is being set as active, deactivate all other schedules
            if ($request->Status && !$schedule->Status) {
                Schedule::where('Status', true)->update(['Status' => false]);
            }

            $schedule->update([
                'EventTimeIn' => $request->EventTimeIn,
                'EventTimeout' => $request->EventTimeout,
                'Description' => $request->Description,
                'Status' => $request->Status,
                'meta_data' => json_encode(array_merge(
                    json_decode($schedule->meta_data, true) ?? [],
                    [
                        'updated_by' => auth()->user()->name,
                        'updated_at' => now()->toDateTimeString(),
                        'notes' => $request->notes ?? null
                    ]
                ))
            ]);

            DB::commit();

            return redirect()->route('schedules.index')
                ->with('success', 'Schedule updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while updating the schedule');
        }
    }

    public function destroy(Schedule $schedule)
    {
        try {
            DB::beginTransaction();

            $schedule->update([
                'Status' => false,
                'meta_data' => json_encode(array_merge(
                    json_decode($schedule->meta_data, true) ?? [],
                    [
                        'deleted_by' => auth()->user()->name,
                        'deleted_at' => now()->toDateTimeString()
                    ]
                ))
            ]);

            $schedule->delete();

            DB::commit();

            return redirect()->route('schedules.index')
                ->with('success', 'Schedule deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while deleting the schedule');
        }
    }

    public function toggleStatus(Schedule $schedule)
    {
        try {
            DB::beginTransaction();

            // If activating this schedule, deactivate all others
            if (!$schedule->Status) {
                Schedule::where('Status', true)->update(['Status' => false]);
            }

            $schedule->update([
                'Status' => !$schedule->Status,
                'meta_data' => json_encode(array_merge(
                    json_decode($schedule->meta_data, true) ?? [],
                    [
                        'status_updated_by' => auth()->user()->name,
                        'status_updated_at' => now()->toDateTimeString(),
                        'previous_status' => $schedule->Status
                    ]
                ))
            ]);

            DB::commit();

            return redirect()->route('schedules.index')
                ->with('success', 'Schedule status updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while updating the schedule status');
        }
    }

    public function report(Request $request)
    {
        $query = Schedule::query();

        // Status filter
        if ($request->filled('status')) {
            $query->where('Status', $request->status);
        }

        $schedules = $query->latest()->paginate(15);

        // Get statistics
        $stats = [
            'total_active' => Schedule::where('Status', true)->count(),
            'total_inactive' => Schedule::where('Status', false)->count(),
            'morning_schedules' => Schedule::where('EventTimeIn', '<', '12:00:00')->count(),
            'afternoon_schedules' => Schedule::where('EventTimeIn', '>=', '12:00:00')->count()
        ];

        // Get time distribution
        $timeDistribution = Schedule::select(
            DB::raw('HOUR(EventTimeIn) as hour'),
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Get duration distribution
        $durationDistribution = Schedule::select(
            DB::raw('TIMESTAMPDIFF(HOUR, EventTimeIn, EventTimeout) as duration'),
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('duration')
            ->orderBy('duration')
            ->get();

        return view('schedules.report', compact(
            'schedules',
            'stats',
            'timeDistribution',
            'durationDistribution'
        ));
    }
}
