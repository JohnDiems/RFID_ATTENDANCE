<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/RFIDLOGO.png') }}">
    <title>RFID Attendance System - Login</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Roboto+Mono:wght@500&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        violet: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                        }
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        mono: ['Roboto Mono', 'monospace']
                    },
                    animation: {
                        'float': 'float 3s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%237c3aed' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 50;
        }
        
        .toast {
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.3s ease-in-out;
        }
        
        .toast.show {
            transform: translateX(0);
            opacity: 1;
        }
    </style>
</head>

<body class="min-h-screen bg-pattern bg-violet-50 font-sans flex items-center justify-center p-4">
    <!-- Toast Container -->
    <div class="toast-container" id="toast-container"></div>
    
    <!-- Clock -->
    <div class="fixed top-4 left-4 text-violet-700 font-mono hidden md:block">
        <div id="time" class="text-2xl font-bold"></div>
        <div id="date" class="text-sm"></div>
    </div>

    <div class="w-full max-w-4xl">
        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-300 hover:shadow-violet-200/50">
            <div class="flex flex-col md:flex-row">
                <!-- Left side - Logo and School Info -->
                <div class="w-full md:w-5/12 bg-gray-50 p-8 flex flex-col items-center justify-center relative">
                    <!-- Decorative Elements -->
                    <div class="absolute top-0 left-0 w-20 h-20 bg-violet-100 rounded-br-full opacity-50"></div>
                    <div class="absolute bottom-0 right-0 w-20 h-20 bg-violet-100 rounded-tl-full opacity-50"></div>
                    
                    <img class="w-3/4 mb-6 animate-float relative z-10" src="{{ asset('images/ASSUMPTION.png') }}" alt="School Logo">
                    <div class="text-center relative z-10">
                        <h2 class="text-2xl font-bold text-violet-800">RFID ATTENDANCE</h2>
                        <p class="text-violet-600 mt-2">SYSTEM</p>
                    </div>
                </div>
                
                <!-- Right side - Login Form -->
                <div class="w-full md:w-7/12 bg-gradient-to-br from-violet-600 to-violet-700 p-8 md:p-12 relative overflow-hidden">
                    <!-- Decorative Elements -->
                    <div class="absolute -top-24 -right-24 w-48 h-48 bg-violet-500 rounded-full opacity-30"></div>
                    <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-violet-500 rounded-full opacity-30"></div>
                    
                    <div class="text-center mb-8 relative z-10">
                        <h1 class="text-3xl font-bold text-white mb-2">Welcome Back</h1>
                        <p class="text-violet-100">Please sign in to continue</p>
                    </div>
                    
                    <!-- Login Form -->
                    <form id="loginForm" action="{{ route('login') }}" method="post" class="space-y-6 relative z-10">
                        @csrf
                        
                        <!-- Email Input -->
                        <div class="transform transition-all duration-300 hover:translate-x-1">
                            <label for="email" class="block text-violet-100 text-sm font-medium mb-2">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-violet-300"></i>
                                </div>
                                <input type="email" id="email" name="email" 
                                    class="w-full pl-10 pr-3 py-3 rounded-lg bg-violet-50 border border-violet-200 focus:outline-none focus:ring-2 focus:ring-violet-300 focus:border-transparent shadow-sm" 
                                    placeholder="Enter your email address" required>
                            </div>
                        </div>
                        
                        <!-- Password Input -->
                        <div class="transform transition-all duration-300 hover:translate-x-1">
                            <label for="password" class="block text-violet-100 text-sm font-medium mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-violet-300"></i>
                                </div>
                                <input type="password" id="password" name="password" 
                                    class="w-full pl-10 pr-3 py-3 rounded-lg bg-violet-50 border border-violet-200 focus:outline-none focus:ring-2 focus:ring-violet-300 focus:border-transparent shadow-sm" 
                                    placeholder="Enter your password" required>
                            </div>
                        </div>
                        
                        <!-- Login Button -->
                        <div class="pt-2">
                            <button type="submit" 
                                class="w-full bg-white text-violet-700 font-bold py-3 px-4 rounded-lg hover:bg-violet-50 transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-violet-300 focus:ring-opacity-50 shadow-md">
                                <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-6 text-violet-700">
            <p>&copy; 2025 RFID Attendance System. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Show toast message function
        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            
            // Create toast element
            const toast = document.createElement('div');
            
            // Set toast classes based on type
            let bgColor = 'bg-violet-100 border-violet-500 text-violet-700';
            let icon = 'info-circle';
            
            switch(type) {
                case 'success':
                    bgColor = 'bg-green-100 border-green-500 text-green-700';
                    icon = 'check-circle';
                    break;
                case 'error':
                    bgColor = 'bg-red-100 border-red-500 text-red-700';
                    icon = 'exclamation-circle';
                    break;
            }
            
            toast.className = `toast ${bgColor} border-l-4 p-4 mb-3 rounded shadow-md`;
            
            // Set toast content
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${icon} mr-3"></i>
                    <span>${message}</span>
                </div>
            `;
            
            // Add to container
            container.appendChild(toast);
            
            // Trigger reflow and add show class
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);
            
            // Remove after 5 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 5000);
        }
        
        // Display error message as toast if exists
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('error'))
                showToast("{{ session('error') }}", 'error');
            @endif
            
            // Update clock
            function updateClock() {
                const now = new Date();
                const timeElement = document.getElementById('time');
                const dateElement = document.getElementById('date');
                
                if (timeElement && dateElement) {
                    timeElement.textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                    dateElement.textContent = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                }
            }
            
            // Update clock immediately and then every second
            updateClock();
            setInterval(updateClock, 1000);
            
            // Form submission with validation
            const loginForm = document.getElementById('loginForm');
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                
                if (!email) {
                    showToast('Please enter your email address', 'error');
                    return;
                }
                
                // Simple email validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    showToast('Please enter a valid email address', 'error');
                    return;
                }
                
                if (!password) {
                    showToast('Please enter your password', 'error');
                    return;
                }
                
                showToast('Signing in...', 'info');
                this.submit();
            });
        });
    </script>
</body>
</html>
