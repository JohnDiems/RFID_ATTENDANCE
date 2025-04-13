@extends('Layout.app')

@section('title', 'Create Profile')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.profiles') }}" class="text-violet-600 hover:text-violet-800 mr-4 flex items-center transition-all duration-300 ease-in-out transform hover:-translate-x-1">
            <i class="fas fa-arrow-left mr-2"></i> Back to Profiles
        </a>
        <h1 class="text-3xl font-bold text-violet-800">Create Profile</h1>
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
            <p class="text-violet-100 text-sm">Enter the profile information below</p>
        </div>
        
        <form action="{{ route('admin.profiles.store') }}" method="POST" class="px-6 pb-6" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="flex flex-col items-center">
                    <h3 class="text-lg font-semibold text-violet-800 mb-3">Profile Photo</h3>
                    <x-avatar-upload />
                    <p class="text-center text-sm text-gray-500 mt-3">Student identification photo</p>
                </div>
                
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative">
                        <label for="FullName" class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-600">*</span></label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" name="FullName" id="FullName" value="{{ old('FullName') }}" 
                                class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('FullName') border-red-500 @enderror text-gray-700" 
                                required>
                        </div>
                        @error('FullName')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <label for="StudentRFID" class="block text-sm font-medium text-gray-700 mb-1">RFID Number <span class="text-red-600">*</span></label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-gray-400"></i>
                            </div>
                            <input type="text" name="StudentRFID" id="StudentRFID" value="{{ old('StudentRFID') }}" 
                                class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('StudentRFID') border-red-500 @enderror text-gray-700">
                            </div>
                            @error('StudentRFID')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    <div class="relative">
                        <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">Student ID <span class="text-red-600">*</span></label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-hashtag text-gray-400"></i>
                            </div>
                            <input type="text" name="student_id" id="student_id" value="{{ old('student_id') }}" 
                                class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('student_id') border-red-500 @enderror text-gray-700" 
                                required>
                        </div>
                        @error('student_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <label for="Gender" class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-600">*</span></label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-venus-mars text-gray-400"></i>
                            </div>
                            <select name="Gender" id="Gender" class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('Gender') border-red-500 @enderror text-gray-700">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        @error('Gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Birth Date <span class="text-red-600">*</span></label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" 
                                class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('birth_date') border-red-500 @enderror text-gray-700" 
                                required>
                        </div>
                        @error('birth_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <label for="blood_type" class="block text-sm font-medium text-gray-700 mb-1">Blood Type <span class="text-red-600">*</span></label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-tint text-gray-400"></i>
                            </div>
                            <select name="blood_type" id="blood_type" class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('blood_type') border-red-500 @enderror text-gray-700">
                                <option value="">Select Blood Type</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                        @error('blood_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <label for="Parent" class="block text-sm font-medium text-gray-700 mb-1">Parent/Guardian Name <span class="text-red-600">*</span></label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user-friends text-gray-400"></i>
                            </div>
                            <input type="text" name="Parent" id="Parent" value="{{ old('Parent') }}" 
                                class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('Parent') border-red-500 @enderror text-gray-700" 
                                required>
                        </div>
                        @error('Parent')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <label for="EmergencyNumber" class="block text-sm font-medium text-gray-700 mb-1">Emergency Contact Number <span class="text-red-600">*</span></label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-phone-alt text-gray-400"></i>
                            </div>
                            <input type="text" name="EmergencyNumber" id="EmergencyNumber" value="{{ old('EmergencyNumber') }}" 
                                class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('EmergencyNumber') border-red-500 @enderror text-gray-700" 
                                required>
                        </div>
                        @error('EmergencyNumber')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <label for="EmergencyAddress" class="block text-sm font-medium text-gray-700 mb-1">Emergency Address <span class="text-red-600">*</span></label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                            </div>
                            <textarea name="EmergencyAddress" id="EmergencyAddress" rows="3" 
                                class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('EmergencyAddress') border-red-500 @enderror text-gray-700" 
                                required>{{ old('EmergencyAddress') }}</textarea>
                        </div>
                        @error('EmergencyAddress')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-graduation-cap text-violet-600 mr-2"></i> Academic Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                        <div>
                            <label for="Course" class="block text-sm font-medium text-gray-700 mb-1">Course <span class="text-red-600">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-book text-gray-400"></i>
                                </div>
                                <input type="text" name="Course" id="Course" value="{{ old('Course') }}" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('Course') border-red-500 @enderror text-gray-700" 
                                    required>
                            </div>
                            @error('Course')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="YearLevel" class="block text-sm font-medium text-gray-700 mb-1">Year Level <span class="text-red-600">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-layer-group text-gray-400"></i>
                                </div>
                                <input type="text" name="YearLevel" id="YearLevel" value="{{ old('YearLevel') }}" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('YearLevel') border-red-500 @enderror text-gray-700" 
                                    required>
                            </div>
                            @error('YearLevel')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="Section" class="block text-sm font-medium text-gray-700 mb-1">Section <span class="text-red-600">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-users text-gray-400"></i>
                                </div>
                                <input type="text" name="Section" id="Section" value="{{ old('Section') }}" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('Section') border-red-500 @enderror text-gray-700" 
                                    required>
                            </div>
                            @error('Section')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="Department" class="block text-sm font-medium text-gray-700 mb-1">Department <span class="text-red-600">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-building text-gray-400"></i>
                                </div>
                                <input type="text" name="Department" id="Department" value="{{ old('Department') }}" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('Department') border-red-500 @enderror text-gray-700" 
                                    required>
                            </div>
                            @error('Department')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="enrollment_status" class="block text-sm font-medium text-gray-700 mb-1">Enrollment Status <span class="text-red-600">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user-check text-gray-400"></i>
                                </div>
                                <select name="enrollment_status" id="enrollment_status" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('enrollment_status') border-red-500 @enderror text-gray-700" 
                                    required>
                                    <option value="">Select Status</option>
                                    <option value="enrolled">Enrolled</option>
                                    <option value="graduated">Graduated</option>
                                    <option value="withdrawn">Withdrawn</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                            </div>
                            @error('enrollment_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-address-book text-violet-600 mr-2"></i> Contact Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                        <div>
                            <label for="EmailAddress" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-600">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" name="EmailAddress" id="EmailAddress" value="{{ old('EmailAddress') }}" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('EmailAddress') border-red-500 @enderror text-gray-700" 
                                    required>
                            </div>
                            @error('EmailAddress')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="ContactNumber" class="block text-sm font-medium text-gray-700 mb-1">Contact Number <span class="text-red-600">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400"></i>
                                </div>
                                <input type="text" name="ContactNumber" id="ContactNumber" value="{{ old('ContactNumber') }}" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('ContactNumber') border-red-500 @enderror text-gray-700" 
                                    required>
                            </div>
                            @error('ContactNumber')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="CompleteAddress" class="block text-sm font-medium text-gray-700 mb-1">Complete Address <span class="text-red-600">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                    <i class="fas fa-home text-gray-400"></i>
                                </div>
                                <textarea name="CompleteAddress" id="CompleteAddress" rows="3" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('CompleteAddress') border-red-500 @enderror text-gray-700" 
                                    required>{{ old('CompleteAddress') }}</textarea>
                            </div>
                            @error('CompleteAddress')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="Parent" class="block text-sm font-medium text-gray-700 mb-1">Parent/Guardian Name <span class="text-red-600">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user-friends text-gray-400"></i>
                                </div>
                                <input type="text" name="Parent" id="Parent" value="{{ old('Parent') }}" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('Parent') border-red-500 @enderror text-gray-700" 
                                    required>
                            </div>
                            @error('Parent')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="EmergencyNumber" class="block text-sm font-medium text-gray-700 mb-1">Emergency Contact Number <span class="text-red-600">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone-alt text-gray-400"></i>
                                </div>
                                <input type="text" name="EmergencyNumber" id="EmergencyNumber" value="{{ old('EmergencyNumber') }}" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('EmergencyNumber') border-red-500 @enderror text-gray-700" 
                                    required>
                            </div>
                            @error('EmergencyNumber')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="EmergencyAddress" class="block text-sm font-medium text-gray-700 mb-1">Emergency Address <span class="text-red-600">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                    <i class="fas fa-map-marker-alt text-gray-400"></i>
                                </div>
                                <textarea name="EmergencyAddress" id="EmergencyAddress" rows="3" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('EmergencyAddress') border-red-500 @enderror text-gray-700" 
                                    required>{{ old('EmergencyAddress') }}</textarea>
                            </div>
                            @error('EmergencyAddress')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-user-shield text-violet-600 mr-2"></i> User Account
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                        <div>
                            <label for="create_user" class="block text-sm font-medium text-gray-700 mb-1">Create User Account</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user-plus text-gray-400"></i>
                                </div>
                                <select name="create_user" id="create_user" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 text-gray-700">
                                    <option value="0" {{ old('create_user') == '0' ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('create_user') == '1' ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">User Role</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user-tag text-gray-400"></i>
                                </div>
                                <select name="role" id="role" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 text-gray-700">
                                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Login Email <span class="text-red-600">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-at text-gray-400"></i>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('email') border-red-500 @enderror text-gray-700" 
                                    required>
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username <span class="text-red-600">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user-circle text-gray-400"></i>
                                </div>
                                <input type="text" name="username" id="username" value="{{ old('username') }}" 
                                    class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50 @error('username') border-red-500 @enderror text-gray-700" 
                                    required>
                            </div>
                            @error('username')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mt-10 pt-6 border-t border-gray-200 flex justify-end">
                    <button type="submit" class="bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white font-bold py-3 px-8 rounded-md focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-opacity-50 transition-all duration-300 ease-in-out transform hover:scale-105 flex items-center">
                        <i class="fas fa-save mr-2"></i> Create Profile
                    </button>
                </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Photo upload preview
        const fileInput = document.getElementById('photo');
        const previewContainer = fileInput.closest('.relative').querySelector('div:first-child');
        const fileNameDisplay = document.getElementById('selected-file-name');
        
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                const fileName = this.files[0].name;
                
                // Update file name display
                fileNameDisplay.textContent = fileName;
                
                reader.onload = function(e) {
                    // Remove existing preview
                    while (previewContainer.firstChild) {
                        previewContainer.removeChild(previewContainer.firstChild);
                    }
                    
                    // Create new image preview
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Profile Preview';
                    img.className = 'h-full w-full object-cover rounded-full';
                    previewContainer.appendChild(img);
                };
                
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        const createUserSelect = document.getElementById('create_user');
        const emailField = document.getElementById('email');
        const usernameField = document.getElementById('username');
        const roleField = document.getElementById('role');
        
        function toggleUserFields() {
            const createUser = createUserSelect.value === '1';
            const userFieldsContainer = emailField.closest('.grid');
            
            // Show/hide user account fields based on selection
            emailField.required = createUser;
            usernameField.required = createUser;
            
            // Update visual cues
            document.querySelectorAll('#email, #username').forEach(field => {
                const label = field.previousElementSibling;
                const requiredSpan = label.querySelector('span') || document.createElement('span');
                
                if (createUser) {
                    if (!label.querySelector('span')) {
                        requiredSpan.className = 'text-red-500';
                        requiredSpan.textContent = ' *';
                        label.appendChild(requiredSpan);
                    }
                } else {
                    if (label.querySelector('span')) {
                        label.removeChild(requiredSpan);
                    }
                }
            });
        }
        
        // Initial setup
        toggleUserFields();
        
        // Add event listener for changes
        createUserSelect.addEventListener('change', toggleUserFields);
    });
</script>
@endpush
