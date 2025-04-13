@props(['type' => 'success', 'message' => ''])

@php
    $bgColor = match($type) {
        'success' => 'bg-green-500',
        'error' => 'bg-red-500',
        'warning' => 'bg-yellow-500',
        'info' => 'bg-blue-500',
        default => 'bg-green-500',
    };
    
    $icon = match($type) {
        'success' => 'fa-check-circle',
        'error' => 'fa-exclamation-circle',
        'warning' => 'fa-exclamation-triangle',
        'info' => 'fa-info-circle',
        default => 'fa-check-circle',
    };
@endphp

<div id="toast-notification" class="fixed top-4 right-4 z-50 transform transition-transform duration-300 ease-in-out translate-x-full">
    <div class="flex items-center p-4 mb-4 text-white rounded-lg shadow-lg {{ $bgColor }}">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg">
            <i class="fas {{ $icon }} text-xl"></i>
        </div>
        <div class="ml-3 text-sm font-normal">{{ $message }}</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 text-white rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-white hover:bg-opacity-20 inline-flex h-8 w-8 items-center justify-center" onclick="closeToast()">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<!-- Custom Toast for JavaScript triggers -->
<div id="custom-toast" class="fixed top-4 right-4 z-50 transform transition-transform duration-300 ease-in-out translate-x-full">
    <div class="flex items-center p-4 mb-4 text-white rounded-lg shadow-lg bg-blue-500" id="custom-toast-bg">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg">
            <i class="fas fa-info-circle text-xl" id="custom-toast-icon"></i>
        </div>
        <div class="ml-3 text-sm font-normal" id="custom-toast-message">Notification</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 text-white rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-white hover:bg-opacity-20 inline-flex h-8 w-8 items-center justify-center" onclick="closeCustomToast()">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<script>
    function showToast() {
        const toast = document.getElementById('toast-notification');
        toast.classList.remove('translate-x-full');
        toast.classList.add('translate-x-0');
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            closeToast();
        }, 5000);
    }
    
    function closeToast() {
        const toast = document.getElementById('toast-notification');
        toast.classList.remove('translate-x-0');
        toast.classList.add('translate-x-full');
    }
    
    function showCustomToast(message, type = 'success') {
        const toast = document.getElementById('custom-toast');
        const toastMessage = document.getElementById('custom-toast-message');
        const toastBg = document.getElementById('custom-toast-bg');
        const toastIcon = document.getElementById('custom-toast-icon');
        
        // Set message
        toastMessage.textContent = message;
        
        // Set type-specific styles
        const bgColors = {
            'success': 'bg-green-500',
            'error': 'bg-red-500',
            'warning': 'bg-yellow-500',
            'info': 'bg-blue-500'
        };
        
        const icons = {
            'success': 'fa-check-circle',
            'error': 'fa-exclamation-circle',
            'warning': 'fa-exclamation-triangle',
            'info': 'fa-info-circle'
        };
        
        // Remove all possible background classes
        Object.values(bgColors).forEach(bgClass => {
            toastBg.classList.remove(bgClass);
        });
        
        // Add the correct background class
        toastBg.classList.add(bgColors[type] || bgColors['success']);
        
        // Update icon
        toastIcon.className = `fas ${icons[type] || icons['success']} text-xl`;
        
        // Show toast
        toast.classList.remove('translate-x-full');
        toast.classList.add('translate-x-0');
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            closeCustomToast();
        }, 5000);
    }
    
    function closeCustomToast() {
        const toast = document.getElementById('custom-toast');
        toast.classList.remove('translate-x-0');
        toast.classList.add('translate-x-full');
    }
    
    // Show toast on page load if it exists
    document.addEventListener('DOMContentLoaded', () => {
        if (document.getElementById('toast-notification').querySelector('.text-sm').textContent.trim() !== '') {
            showToast();
        }
        
        // Make function available globally
        window.showCustomToast = showCustomToast;
        window.closeCustomToast = closeCustomToast;
    });
</script>
