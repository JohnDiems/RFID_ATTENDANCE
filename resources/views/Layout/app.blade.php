<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'RFID Attendance System')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f3f4f6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .sidebar-active {
            background-color: #8b5cf6;
            color: white;
        }
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .content-wrapper {
            flex: 1;
        }
    </style>
    
    @stack('styles')
</head>
<body class="antialiased text-gray-800">
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        @include('components.sidebar')
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden main-content">
            <!-- Top Navigation -->
            @include('components.topnav')
            
            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 content-wrapper">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Toast Messages -->
    @if(session('success'))
        <x-toast type="success" message="{{ session('success') }}" />
    @endif
    
    @if(session('error'))
        <x-toast type="error" message="{{ session('error') }}" />
    @endif
    
    @if(session('info'))
        <x-toast type="info" message="{{ session('info') }}" />
    @endif
    
    @if(session('warning'))
        <x-toast type="warning" message="{{ session('warning') }}" />
    @endif
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html>
