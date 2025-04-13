@extends('Layout.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-violet-800">Dashboard</h1>
        <p class="text-gray-600">Welcome to the RFID Attendance System Dashboard</p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @include('components.dashboard.stat-card', [
            'title' => 'Total Students',
            'value' => $stats['total_students'],
            'color' => 'violet',
            'badge' => $stats['active_students'] . ' active',
            'badgeColor' => 'violet'
        ])

        @include('components.dashboard.stat-card', [
            'title' => 'Today\'s Attendance',
            'value' => $stats['today_attendance'],
            'color' => 'emerald',
            'badge' => $stats['today_late'] . ' late',
            'badgeColor' => 'red'
        ])

        @include('components.dashboard.stat-card', [
            'title' => 'Today\'s Lunch',
            'value' => $stats['today_lunch'],
            'color' => 'blue'
        ])

        @include('components.dashboard.stat-card', [
            'title' => 'Active Schedules',
            'value' => $stats['active_schedules'],
            'color' => 'amber',
            'badge' => $stats['total_schedules'] . ' total',
            'badgeColor' => 'amber'
        ])
    </div>

    <!-- Charts & Trends -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Attendance Trend -->
        @include('components.dashboard.chart-card', [
            'title' => 'Attendance Trend (Last 7 Days)',
            'chartId' => 'attendanceChart'
        ])

        <!-- Lunch Trend -->
        @include('components.dashboard.chart-card', [
            'title' => 'Lunch Trend (Last 7 Days)',
            'chartId' => 'lunchChart'
        ])
    </div>

    <!-- Recent Activity & Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Activity -->
        <div class="lg:col-span-1">
            @include('components.dashboard.activity-card', [
                'title' => 'Recent Activity',
                'activities' => $recentActivity
            ])
        </div>

        <!-- Students by Year Level -->
        @include('components.dashboard.chart-card', [
            'title' => 'Students by Year Level',
            'chartId' => 'yearLevelChart'
        ])

        <!-- Students by Course -->
        @include('components.dashboard.chart-card', [
            'title' => 'Students by Course',
            'chartId' => 'courseChart'
        ])
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Prepare data for charts
    const attendanceData = {
        labels: {!! json_encode($attendanceTrend->pluck('date')) !!},
        datasets: [
            {
                label: 'On Time',
                data: {!! json_encode($attendanceTrend->pluck('on_time')) !!},
                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 2
            },
            {
                label: 'Late',
                data: {!! json_encode($attendanceTrend->pluck('late')) !!},
                backgroundColor: 'rgba(239, 68, 68, 0.2)',
                borderColor: 'rgba(239, 68, 68, 1)',
                borderWidth: 2
            }
        ]
    };

    const lunchData = {
        labels: {!! json_encode($lunchTrend->pluck('date')) !!},
        datasets: [
            {
                label: 'Completed',
                data: {!! json_encode($lunchTrend->pluck('completed')) !!},
                backgroundColor: 'rgba(124, 58, 237, 0.2)',
                borderColor: 'rgba(124, 58, 237, 1)',
                borderWidth: 2
            },
            {
                label: 'Ongoing',
                data: {!! json_encode($lunchTrend->pluck('ongoing')) !!},
                backgroundColor: 'rgba(245, 158, 11, 0.2)',
                borderColor: 'rgba(245, 158, 11, 1)',
                borderWidth: 2
            }
        ]
    };

    const yearLevelData = {
        labels: {!! json_encode($studentsByYear->pluck('YearLevel')) !!},
        datasets: [{
            data: {!! json_encode($studentsByYear->pluck('total')) !!},
            backgroundColor: [
                'rgba(124, 58, 237, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(59, 130, 246, 0.8)',
                'rgba(239, 68, 68, 0.8)'
            ],
            borderWidth: 1
        }]
    };

    const courseData = {
        labels: {!! json_encode($studentsByCourse->pluck('Course')) !!},
        datasets: [{
            data: {!! json_encode($studentsByCourse->pluck('total')) !!},
            backgroundColor: [
                'rgba(124, 58, 237, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(59, 130, 246, 0.8)',
                'rgba(239, 68, 68, 0.8)',
                'rgba(236, 72, 153, 0.8)',
                'rgba(75, 85, 99, 0.8)'
            ],
            borderWidth: 1
        }]
    };

    // Create charts
    document.addEventListener('DOMContentLoaded', function() {
        // Attendance Chart
        new Chart(document.getElementById('attendanceChart'), {
            type: 'bar',
            data: attendanceData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { stacked: true },
                    y: { stacked: true }
                }
            }
        });

        // Lunch Chart
        new Chart(document.getElementById('lunchChart'), {
            type: 'line',
            data: lunchData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                tension: 0.3
            }
        });

        // Year Level Chart
        new Chart(document.getElementById('yearLevelChart'), {
            type: 'doughnut',
            data: yearLevelData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Course Chart
        new Chart(document.getElementById('courseChart'), {
            type: 'pie',
            data: courseData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        display: false
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
