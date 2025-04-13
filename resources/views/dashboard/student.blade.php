@extends('Layout.app')

@section('title', 'Student Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-violet-800">Student Dashboard</h1>
        <p class="text-gray-600">Welcome, {{ $profile->FullName }}</p>
    </div>

    <!-- Student Profile Card -->
    @include('components.dashboard.profile-card', ['profile' => $profile])

    <!-- Today's Status -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Attendance Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Today's Attendance</h3>
            @if($attendanceStats['today'])
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-500">Time In</div>
                        <div class="text-xl font-semibold">{{ $attendanceStats['today']->time_in ? $attendanceStats['today']->time_in->format('h:i A') : 'Not recorded' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Time Out</div>
                        <div class="text-xl font-semibold">{{ $attendanceStats['today']->time_out ? $attendanceStats['today']->time_out->format('h:i A') : 'Not recorded' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Status</div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $attendanceStats['today']->status == 'late' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ ucfirst($attendanceStats['today']->status) }}
                        </span>
                    </div>
                </div>
            @else
                <div class="text-center py-4">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No attendance recorded today</h3>
                    <p class="mt-1 text-sm text-gray-500">Your attendance has not been recorded for today.</p>
                </div>
            @endif
        </div>

        <!-- Lunch Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Today's Lunch</h3>
            @if($lunchStats['today'])
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-500">Lunch In</div>
                        <div class="text-xl font-semibold">{{ $lunchStats['today']->time_in ? $lunchStats['today']->time_in->format('h:i A') : 'Not recorded' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Lunch Out</div>
                        <div class="text-xl font-semibold">{{ $lunchStats['today']->time_out ? $lunchStats['today']->time_out->format('h:i A') : 'Not recorded' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Status</div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lunchStats['today']->isComplete() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $lunchStats['today']->isComplete() ? 'Complete' : 'Ongoing' }}
                        </span>
                    </div>
                </div>
            @else
                <div class="text-center py-4">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No lunch recorded today</h3>
                    <p class="mt-1 text-sm text-gray-500">Your lunch break has not been recorded for today.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Monthly Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Attendance</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-violet-50 rounded-lg p-4">
                    <div class="text-sm text-gray-500">Total Days</div>
                    <div class="text-2xl font-bold text-violet-700">{{ $attendanceStats['monthly_attendance'] }}</div>
                </div>
                <div class="bg-red-50 rounded-lg p-4">
                    <div class="text-sm text-gray-500">Late Days</div>
                    <div class="text-2xl font-bold text-red-700">{{ $attendanceStats['monthly_late'] }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Lunch</h3>
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="text-sm text-gray-500">Total Lunches</div>
                <div class="text-2xl font-bold text-blue-700">{{ $lunchStats['monthly_lunches'] }}</div>
            </div>
        </div>
    </div>

    <!-- Attendance History -->
    @include('components.dashboard.table-card', [
        'title' => 'Attendance History (Last 7 Days)',
        'headers' => ['Date', 'Status', 'Time In', 'Time Out']
    ])
        @foreach($attendanceTrend as $record)
        <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($record['date'])->format('M d, Y') }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                @if($record['status'] == 'absent')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Absent</span>
                @elseif($record['status'] == 'late')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Late</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ ucfirst($record['status']) }}</span>
                @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record['time_in'] ? \Carbon\Carbon::parse($record['time_in'])->format('h:i A') : '-' }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record['time_out'] ? \Carbon\Carbon::parse($record['time_out'])->format('h:i A') : '-' }}</td>
        </tr>
        @endforeach
    @include('components.dashboard.table-footer')

    <!-- Lunch History -->
    @include('components.dashboard.table-card', [
        'title' => 'Lunch History (Last 7 Days)',
        'headers' => ['Date', 'Status', 'Lunch In', 'Lunch Out']
    ])
        @foreach($lunchTrend as $record)
        <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($record['date'])->format('M d, Y') }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                @if($record['status'] == 'none')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">None</span>
                @elseif($record['status'] == 'complete')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Complete</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">{{ ucfirst($record['status']) }}</span>
                @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record['time_in'] ? \Carbon\Carbon::parse($record['time_in'])->format('h:i A') : '-' }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record['time_out'] ? \Carbon\Carbon::parse($record['time_out'])->format('h:i A') : '-' }}</td>
        </tr>
        @endforeach
    @include('components.dashboard.table-footer')
</div>
@endsection
