@extends('Layout.app')

@section('title', 'Profile Details')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.profiles') }}" class="text-violet-600 hover:text-violet-800 mr-4">
            <i class="fas fa-arrow-left"></i> Back to Profiles
        </a>
        <h1 class="text-3xl font-bold text-violet-800">Profile Details</h1>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Info Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-violet-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-white">Personal Information</h2>
                        <a href="{{ route('admin.profiles.edit', $profile) }}" class="bg-white text-violet-600 hover:bg-violet-100 px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        @if($profile->photo && Storage::disk('public')->exists($profile->photo))
                            <div class="h-20 w-20 rounded-full overflow-hidden bg-violet-100 mr-4">
                                <img src="{{ asset('storage/' . $profile->photo) }}" alt="{{ $profile->FullName }}" class="h-full w-full object-cover">
                            </div>
                        @else
                            <div class="h-20 w-20 rounded-full bg-violet-100 flex items-center justify-center text-violet-800 text-2xl font-bold mr-4">
                                {{ strtoupper(substr($profile->FullName, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $profile->FullName }}</h3>
                            <p class="text-gray-600">{{ $profile->student_id }}</p>
                            <div class="mt-1">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($profile->Status) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                    {{ $profile->Status ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Basic Information</h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500">Gender</p>
                                    <p class="font-medium">{{ ucfirst($profile->Gender) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Birth Date</p>
                                    <p class="font-medium">
                                        @if($profile->birth_date)
                                            {{ $profile->birth_date->format('F d, Y') }}
                                            <span class="text-xs text-gray-500 ml-1">({{ $profile->birth_date->age }} years old)</span>
                                        @else
                                            Not set
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Blood Type</p>
                                    <p class="font-medium">{{ $profile->blood_type ?? 'Not set' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Contact Number</p>
                                    <p class="font-medium">{{ $profile->ContactNumber }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Email Address</p>
                                    <p class="font-medium">{{ $profile->EmailAddress ?? ($profile->user ? $profile->user->email : 'Not set') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Academic Information</h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500">Course</p>
                                    <p class="font-medium">{{ $profile->Course }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Year Level</p>
                                    <p class="font-medium">{{ $profile->YearLevel }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Section</p>
                                    <p class="font-medium">{{ $profile->section ?? 'Not assigned' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">RFID Number</p>
                                    <p class="font-medium">{{ $profile->StudentRFID ?? 'Not assigned' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Address</h4>
                        <p class="font-medium">{{ $profile->CompleteAddress }}</p>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact Card -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
                <div class="bg-orange-500 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Emergency Contact Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Parent/Guardian</p>
                            <p class="font-medium">{{ $profile->Parent }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Emergency Contact Number</p>
                            <p class="font-medium">{{ $profile->EmergencyNumber }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Emergency Address</p>
                        <p class="font-medium">{{ $profile->EmergencyAddress }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Card -->
        <div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-blue-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Recent Activity</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Attendance Summary</h4>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Present</span>
                                <span class="font-medium">{{ $profile->attendances()->where('status', 'present')->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Late</span>
                                <span class="font-medium">{{ $profile->attendances()->where('status', 'late')->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Absent</span>
                                <span class="font-medium">{{ $profile->attendances()->where('status', 'absent')->count() }}</span>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Recent Attendance</h4>
                            @if($profile->attendances()->count() > 0)
                                <div class="space-y-3">
                                    @foreach($profile->attendances()->latest()->take(5)->get() as $attendance)
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full flex items-center justify-center mr-3
                                                @if($attendance->status == 'present') bg-green-100 text-green-600
                                                @elseif($attendance->status == 'late') bg-yellow-100 text-yellow-600
                                                @else bg-red-100 text-red-600 @endif">
                                                <i class="fas @if($attendance->status == 'present') fa-check
                                                    @elseif($attendance->status == 'late') fa-clock
                                                    @else fa-times @endif"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium">
                                                    {{ ucfirst($attendance->status) }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $attendance->created_at->format('M d, Y g:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No attendance records found.</p>
                            @endif
                        </div>

                        <div class="border-t pt-4">
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Recent Lunch</h4>
                            @if($profile->lunches()->count() > 0)
                                <div class="space-y-3">
                                    @foreach($profile->lunches()->latest()->take(5)->get() as $lunch)
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-3">
                                                <i class="fas fa-utensils"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium">Lunch Recorded</p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $lunch->created_at->format('M d, Y g:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No lunch records found.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($profile->user)
            <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
                <div class="bg-gray-800 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Account Information</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Username</p>
                            <p class="font-medium">{{ $profile->user->username ?? 'Not set' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium">{{ $profile->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Role</p>
                            <p class="font-medium capitalize">{{ $profile->user->role }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Account Status</p>
                            <p class="font-medium capitalize">{{ $profile->user->status }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Last Login</p>
                            <p class="font-medium">{{ $profile->user->last_login_at ? $profile->user->last_login_at->format('M d, Y g:i A') : 'Never' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
