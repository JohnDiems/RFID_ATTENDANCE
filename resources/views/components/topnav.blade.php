<header class="bg-white shadow">
    <div class="flex items-center justify-between px-6 py-3">
        <!-- Mobile Menu Toggle -->
        <button id="mobile-menu-button" class="md:hidden text-gray-600 hover:text-gray-900">
            <i class="fas fa-bars text-xl"></i>
        </button>
        
        <!-- Search Bar -->
        <div class="hidden md:flex items-center flex-1 mx-4">
            <div class="relative w-full max-w-md">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fas fa-search text-gray-400"></i>
                </span>
                <input type="text" class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent" placeholder="Search...">
            </div>
        </div>
        
        <!-- Right Navigation -->
        <div class="flex items-center space-x-4">
            <!-- Current Date & Time -->
            <div class="hidden md:block text-sm text-gray-600">
                <span id="current-date">{{ now()->format('F d, Y') }}</span>
                <span id="current-time">{{ now()->format('h:i A') }}</span>
            </div>
            
            <!-- Notifications -->
            <div class="relative">
                <button class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">3</span>
                </button>
            </div>
            
            <!-- User Menu -->
            <div class="relative" id="user-menu">
                <button id="user-menu-button" class="flex items-center space-x-2 focus:outline-none">
                    <img class="h-8 w-8 rounded-full object-cover" src="{{ auth()->user()->profile->photo ?? asset('images/default-avatar.png') }}" alt="User Avatar">
                    <span class="hidden md:block text-sm font-medium">{{ auth()->user()->name ?? 'Guest' }}</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
                
                <!-- Dropdown Menu -->
                <div id="user-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-violet-100">
                        <i class="fas fa-user-circle mr-2"></i> My Profile
                    </a>
                    <a href="{{ route('profile.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-violet-100">
                        <i class="fas fa-cog mr-2"></i> Settings
                    </a>
                    <div class="border-t border-gray-100"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-violet-100">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Sidebar (Hidden by default) -->
<div id="mobile-sidebar" class="fixed inset-0 z-40 hidden">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="absolute inset-y-0 left-0 w-64 bg-violet-900 text-white">
        <div class="flex justify-between items-center p-4 border-b border-violet-800">
            <h1 class="text-xl font-bold">RFID Attendance</h1>
            <button id="close-sidebar" class="text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Mobile Navigation (Same as sidebar) -->
        <nav class="mt-5">
            <!-- Same content as sidebar.blade.php but for mobile -->
            @include('components.sidebar-content')
        </nav>
    </div>
</div>

<script>
    // Mobile menu toggle
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const closeSidebar = document.getElementById('close-sidebar');
        
        if (mobileMenuButton && mobileSidebar && closeSidebar) {
            mobileMenuButton.addEventListener('click', function() {
                mobileSidebar.classList.toggle('hidden');
            });
            
            closeSidebar.addEventListener('click', function() {
                mobileSidebar.classList.add('hidden');
            });
        }
        
        // User dropdown menu toggle
        const userMenuButton = document.getElementById('user-menu-button');
        const userDropdown = document.getElementById('user-dropdown');
        
        if (userMenuButton && userDropdown) {
            userMenuButton.addEventListener('click', function() {
                userDropdown.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const userMenu = document.getElementById('user-menu');
                if (userMenu && !userMenu.contains(event.target)) {
                    userDropdown.classList.add('hidden');
                }
            });
        }
        
        // Update current time
        function updateTime() {
            const now = new Date();
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                timeElement.textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            }
        }
        
        setInterval(updateTime, 1000);
        updateTime();
    });
</script>
