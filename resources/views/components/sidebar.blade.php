<aside class="bg-violet-900 text-white w-64 flex-shrink-0 hidden md:block overflow-y-auto">
    <div class="p-6">
        <h1 class="text-2xl font-bold">RFID Attendance</h1>
    </div>
    
    <nav class="mt-5">
        <div class="px-4 py-2 text-xs text-violet-300 uppercase font-semibold">
            Main
        </div>
        
        @php
            $role = auth()->user()->role ?? 'guest';
            $currentRoute = Route::currentRouteName();
        @endphp
        
        <!-- Common Links -->
        <a href="{{ route('home') }}" class="flex items-center px-6 py-3 hover:bg-violet-800 {{ $currentRoute == 'home' ? 'sidebar-active' : '' }}">
            <i class="fas fa-home mr-3"></i>
            <span>Home</span>
        </a>
        
        <!-- Admin Links -->
        @if($role == 'admin')
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 hover:bg-violet-800 {{ $currentRoute == 'admin.dashboard' ? 'sidebar-active' : '' }}">
                <i class="fas fa-tachometer-alt mr-3"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="{{ route('admin.users') }}" class="flex items-center px-6 py-3 hover:bg-violet-800 {{ $currentRoute == 'admin.users' ? 'sidebar-active' : '' }}">
                <i class="fas fa-users mr-3"></i>
                <span>Users</span>
            </a>
            
            <a href="{{ route('admin.profiles') }}" class="flex items-center px-6 py-3 hover:bg-violet-800 {{ $currentRoute == 'admin.profiles' ? 'sidebar-active' : '' }}">
                <i class="fas fa-id-card mr-3"></i>
                <span>Profiles</span>
            </a>
            
            <a href="{{ route('admin.schedules') }}" class="flex items-center px-6 py-3 hover:bg-violet-800 {{ $currentRoute == 'admin.schedules' ? 'sidebar-active' : '' }}">
                <i class="fas fa-calendar-alt mr-3"></i>
                <span>Schedules</span>
            </a>
            
            <a href="{{ route('admin.reports') }}" class="flex items-center px-6 py-3 hover:bg-violet-800 {{ $currentRoute == 'admin.reports' ? 'sidebar-active' : '' }}">
                <i class="fas fa-chart-bar mr-3"></i>
                <span>Reports</span>
            </a>
        @endif
        
        <!-- Teacher Links -->
        @if($role == 'teacher')
            <a href="{{ route('teacher.dashboard') }}" class="flex items-center px-6 py-3 hover:bg-violet-800 {{ $currentRoute == 'teacher.dashboard' ? 'sidebar-active' : '' }}">
                <i class="fas fa-tachometer-alt mr-3"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="{{ route('teacher.attendance') }}" class="flex items-center px-6 py-3 hover:bg-violet-800 {{ $currentRoute == 'teacher.attendance' ? 'sidebar-active' : '' }}">
                <i class="fas fa-clipboard-check mr-3"></i>
                <span>Attendance</span>
            </a>
            
            <a href="{{ route('teacher.students') }}" class="flex items-center px-6 py-3 hover:bg-violet-800 {{ $currentRoute == 'teacher.students' ? 'sidebar-active' : '' }}">
                <i class="fas fa-user-graduate mr-3"></i>
                <span>Students</span>
            </a>
            
            <a href="{{ route('teacher.reports') }}" class="flex items-center px-6 py-3 hover:bg-violet-800 {{ $currentRoute == 'teacher.reports' ? 'sidebar-active' : '' }}">
                <i class="fas fa-file-alt mr-3"></i>
                <span>Reports</span>
            </a>
        @endif
        
        <!-- Student Links -->
        @if($role == 'student')
            <a href="{{ route('student.dashboard') }}" class="flex items-center px-6 py-3 hover:bg-violet-800 {{ $currentRoute == 'student.dashboard' ? 'sidebar-active' : '' }}">
                <i class="fas fa-tachometer-alt mr-3"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="{{ route('student.profile') }}" class="flex items-center px-6 py-3 hover:bg-violet-800 {{ $currentRoute == 'student.profile' ? 'sidebar-active' : '' }}">
                <i class="fas fa-user mr-3"></i>
                <span>My Profile</span>
            </a>
            
            <a href="{{ route('student.attendance.history') }}" class="flex items-center px-6 py-3 hover:bg-violet-800 {{ $currentRoute == 'student.attendance.history' ? 'sidebar-active' : '' }}">
                <i class="fas fa-history mr-3"></i>
                <span>Attendance History</span>
            </a>
            
            <a href="{{ route('student.lunch.history') }}" class="flex items-center px-6 py-3 hover:bg-violet-800 {{ $currentRoute == 'student.lunch.history' ? 'sidebar-active' : '' }}">
                <i class="fas fa-utensils mr-3"></i>
                <span>Lunch History</span>
            </a>
        @endif
        
        <div class="px-4 py-2 mt-6 text-xs text-violet-300 uppercase font-semibold">
            Account
        </div>
        
        <a href="{{ route('profile.edit') }}" class="flex items-center px-6 py-3 hover:bg-violet-800 {{ $currentRoute == 'profile.edit' ? 'sidebar-active' : '' }}">
            <i class="fas fa-user-cog mr-3"></i>
            <span>Settings</span>
        </a>
        
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="flex items-center px-6 py-3 w-full text-left hover:bg-violet-800">
                <i class="fas fa-sign-out-alt mr-3"></i>
                <span>Logout</span>
            </button>
        </form>
    </nav>
</aside>
