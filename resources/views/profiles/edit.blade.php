@extends('Layout.app')

@section('title', 'Edit Profile')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.profiles') }}" class="text-violet-600 hover:text-violet-800 mr-4 flex items-center transition-all duration-300 ease-in-out transform hover:-translate-x-1">
            <i class="fas fa-arrow-left mr-2"></i> Back to Profiles
        </a>
        <h1 class="text-3xl font-bold text-violet-800">Edit Profile</h1>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg p-0 overflow-hidden">
        <div class="bg-gradient-to-r from-violet-600 to-purple-600 px-6 py-4 mb-6">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-user-edit mr-2"></i> Personal Information
            </h2>
            <p class="text-violet-100 text-sm">Update the profile information below</p>
        </div>
        
        <form action="{{ route('admin.profiles.update', $profile) }}" method="POST" class="px-6 pb-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="flex flex-col items-center">
                    <h3 class="text-lg font-semibold text-violet-800 mb-3">Profile Photo</h3>
                    <x-avatar-upload :profile="$profile" />
                    <p class="text-center text-sm text-gray-500 mt-3">Student identification photo</p>
                </div>
                
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative">
                        <label for="FullName" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" name="FullName" id="FullName" value="{{ old('FullName', $profile->FullName) }}" 
                                class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('FullName') border-red-500 @enderror text-gray-700" 
                                required>
                        </div>
                        @error('FullName')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <label for="StudentRFID" class="block text-sm font-medium text-gray-700 mb-1">RFID Number</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-gray-400"></i>
                            </div>
                            <input type="text" name="StudentRFID" id="StudentRFID" value="{{ old('StudentRFID', $profile->StudentRFID) }}" 
                                class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('StudentRFID') border-red-500 @enderror text-gray-700">
                            </div>
                            @error('StudentRFID')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="relative">
                            <label for="Gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-venus-mars text-gray-400"></i>
                                </div>
                                <select name="Gender" id="Gender" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('Gender') border-red-500 @enderror appearance-none text-gray-700" 
                                    required>
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('Gender', $profile->Gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('Gender', $profile->Gender) == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            @error('Gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="relative">
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Birth Date</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                                <input type="date" name="birth_date" id="birth_date" 
                                    value="{{ old('birth_date', $profile->birth_date ? $profile->birth_date->format('Y-m-d') : date('Y-m-d')) }}" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('birth_date') border-red-500 @enderror text-gray-700" 
                                    required>
                            </div>
                            @error('birth_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                @if($profile->birth_date)
                                    Current: {{ $profile->birth_date->format('F d, Y') }} 
                                    <span class="text-xs text-violet-500">({{ $profile->birth_date->age }} years old)</span>
                                @else
                                    Please select a date of birth
                                @endif
                            </p>
                        </div>

                        <div>
                            <label for="blood_type" class="block text-sm font-medium text-gray-700 mb-1">Blood Type</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tint text-gray-400"></i>
                                </div>
                                <select name="blood_type" id="blood_type" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('blood_type') border-red-500 @enderror appearance-none text-gray-700" 
                                    required>
                                <option value="">Select Blood Type</option>
                                <option value="A+" {{ old('blood_type', $profile->blood_type) == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ old('blood_type', $profile->blood_type) == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ old('blood_type', $profile->blood_type) == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ old('blood_type', $profile->blood_type) == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="O+" {{ old('blood_type', $profile->blood_type) == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ old('blood_type', $profile->blood_type) == 'O-' ? 'selected' : '' }}>O-</option>
                                <option value="AB+" {{ old('blood_type', $profile->blood_type) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ old('blood_type', $profile->blood_type) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            @error('blood_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ContactNumber" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400"></i>
                                </div>
                                <input type="text" name="ContactNumber" id="ContactNumber" value="{{ old('ContactNumber', $profile->ContactNumber) }}" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('ContactNumber') border-red-500 @enderror text-gray-700" 
                                    required>
                            </div>
                            @error('ContactNumber')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="Course" class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-book text-gray-400"></i>
                                </div>
                                <input type="text" name="Course" id="Course" value="{{ old('Course', $profile->Course) }}" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('Course') border-red-500 @enderror text-gray-700" 
                                    required>
                            </div>
                            @error('Course')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="YearLevel" class="block text-sm font-medium text-gray-700 mb-1">Year Level</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-layer-group text-gray-400"></i>
                                </div>
                                <input type="text" name="YearLevel" id="YearLevel" value="{{ old('YearLevel', $profile->YearLevel) }}" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('YearLevel') border-red-500 @enderror text-gray-700" 
                                    required>
                            </div>
                            @error('YearLevel')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="CompleteAddress" class="block text-sm font-medium text-gray-700 mb-1">Complete Address</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                            <i class="fas fa-home text-gray-400"></i>
                        </div>
                        <textarea name="CompleteAddress" id="CompleteAddress" rows="3" 
                            class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('CompleteAddress') border-red-500 @enderror text-gray-700" 
                            required>{{ old('CompleteAddress', $profile->CompleteAddress) }}</textarea>
                    </div>
                    @error('CompleteAddress')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-10 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-ambulance text-red-500 mr-2"></i> Emergency Contact Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="Parent" class="block text-sm font-medium text-gray-700 mb-1">Parent/Guardian Name</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user-friends text-gray-400"></i>
                                </div>
                                <input type="text" name="Parent" id="Parent" value="{{ old('Parent', $profile->Parent) }}" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('Parent') border-red-500 @enderror text-gray-700" 
                                    required>
                            </div>
                            @error('Parent')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="EmergencyNumber" class="block text-sm font-medium text-gray-700 mb-1">Emergency Contact Number</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone-alt text-gray-400"></i>
                                </div>
                                <input type="text" name="EmergencyNumber" id="EmergencyNumber" value="{{ old('EmergencyNumber', $profile->EmergencyNumber) }}" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('EmergencyNumber') border-red-500 @enderror text-gray-700" 
                                    required>
                            </div>
                            @error('EmergencyNumber')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="EmergencyAddress" class="block text-sm font-medium text-gray-700 mb-1">Emergency Address</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                            </div>
                            <textarea name="EmergencyAddress" id="EmergencyAddress" rows="3" 
                                class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('EmergencyAddress') border-red-500 @enderror text-gray-700" 
                                required>{{ old('EmergencyAddress', $profile->EmergencyAddress) }}</textarea>
                        </div>
                        @error('EmergencyAddress')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t border-gray-200 flex justify-end">
                    <button type="submit" class="bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white font-bold py-3 px-8 rounded-md focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-opacity-50 transition-all duration-300 ease-in-out transform hover:scale-105 flex items-center">
                        <i class="fas fa-save mr-2"></i> Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
