@extends('Layout.app')

@section('title', 'Reports Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-violet-800 mb-6">Reports Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Attendance Reports Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-violet-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Attendance Reports</h2>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">View and analyze attendance data, track trends, and identify patterns.</p>
                <div class="flex justify-between items-center">
                    <div class="text-violet-600">
                        <i class="fas fa-calendar-check text-3xl"></i>
                    </div>
                    <a href="{{ route('admin.reports.attendance') }}" class="bg-violet-600 hover:bg-violet-700 text-white font-bold py-2 px-4 rounded">
                        View Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Lunch Reports Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-violet-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Lunch Reports</h2>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">Track lunch attendance, monitor meal consumption, and analyze trends.</p>
                <div class="flex justify-between items-center">
                    <div class="text-violet-600">
                        <i class="fas fa-utensils text-3xl"></i>
                    </div>
                    <a href="{{ route('admin.reports.lunch') }}" class="bg-violet-600 hover:bg-violet-700 text-white font-bold py-2 px-4 rounded">
                        View Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Export Data Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-violet-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Export Data</h2>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">Export attendance and lunch data to CSV or Excel for further analysis.</p>
                <div class="mt-4">
                    <form action="{{ route('admin.reports.export') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Report Type</label>
                                <select name="type" id="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50">
                                    <option value="attendance">Attendance</option>
                                    <option value="lunch">Lunch</option>
                                </select>
                            </div>
                            <div>
                                <label for="format" class="block text-sm font-medium text-gray-700 mb-1">Format</label>
                                <select name="format" id="format" class="w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50">
                                    <option value="csv">CSV</option>
                                    <option value="excel">Excel</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-violet-600 hover:bg-violet-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-download mr-2"></i> Export
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Report Settings Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-violet-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Report Settings</h2>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">Configure report settings, notifications, and automated reports.</p>
                <div class="flex justify-between items-center">
                    <div class="text-violet-600">
                        <i class="fas fa-cog text-3xl"></i>
                    </div>
                    <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">
                        Coming Soon
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
