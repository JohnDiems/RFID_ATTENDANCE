@extends('Layout.app')

@section('title', 'Lunch Reports')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.reports') }}" class="text-violet-600 hover:text-violet-800 mr-4">
            <i class="fas fa-arrow-left"></i> Back to Reports
        </a>
        <h1 class="text-3xl font-bold text-violet-800">Lunch Reports</h1>
    </div>

    @if(session('info'))
    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
        <p>{{ session('info') }}</p>
    </div>
    @endif

    <!-- Date Range Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold text-violet-800 mb-4">Filter Options</h2>
        <form action="{{ route('admin.reports.lunch') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate->format('Y-m-d') }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate->format('Y-m-d') }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-violet-600 hover:bg-violet-700 text-white font-bold py-2 px-4 rounded">
                        Apply Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Lunch Summary -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold text-violet-800 mb-4">Lunch Summary</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Total Lunch Card -->
            <div class="bg-violet-50 rounded-lg p-4 border border-violet-200">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-violet-600 font-medium">Total Lunch Records</p>
                        <p class="text-3xl font-bold text-violet-800">{{ $lunchData->sum('total') }}</p>
                    </div>
                    <div class="text-violet-500">
                        <i class="fas fa-utensils text-3xl"></i>
                    </div>
                </div>
            </div>
            
            <!-- Completed Lunch Card -->
            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-green-600 font-medium">Completed (In & Out)</p>
                        <p class="text-3xl font-bold text-green-800">{{ $lunchData->sum('completed_count') }}</p>
                    </div>
                    <div class="text-green-500">
                        <i class="fas fa-check-circle text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lunch Chart -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold text-violet-800 mb-4">Lunch Trend</h2>
        <div class="w-full h-80">
            <canvas id="lunchChart"></canvas>
        </div>
    </div>

    <!-- Lunch Data Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-violet-800">Lunch Data</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Incomplete</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($lunchData as $data)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($data->date)->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $data->total }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $data->completed_count }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $data->total - $data->completed_count }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No lunch data found for the selected date range.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('lunchChart').getContext('2d');
        
        // Prepare data for chart
        const dates = @json($lunchData->pluck('date')->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('M d');
        }));
        const totals = @json($lunchData->pluck('total'));
        const completed = @json($lunchData->pluck('completed_count'));
        const incomplete = @json($lunchData->map(function($data) {
            return $data->total - $data->completed_count;
        }));
        
        const lunchChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: 'Total',
                        data: totals,
                        backgroundColor: 'rgba(124, 58, 237, 0.7)',
                        borderColor: 'rgba(124, 58, 237, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Completed',
                        data: completed,
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Incomplete',
                        data: incomplete,
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
