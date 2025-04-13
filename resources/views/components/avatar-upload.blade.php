@props(['profile' => null, 'name' => 'photo'])
@php
use Illuminate\Support\Facades\Storage;
@endphp

<div class="flex flex-col items-center space-y-4">
    <div class="relative group">
        @if($profile && $profile->photo && Storage::disk('public')->exists($profile->photo))
            <div class="h-32 w-32 rounded-full overflow-hidden bg-violet-100 border-4 border-white shadow-lg">
                <img src="{{ asset('storage/' . $profile->photo) }}" alt="{{ $profile->FullName }}" class="h-full w-full object-cover">
            </div>
        @else
            <div class="h-32 w-32 rounded-full flex items-center justify-center bg-gradient-to-r from-violet-500 to-purple-600 text-white text-4xl font-bold border-4 border-white shadow-lg">
                {{ $profile ? strtoupper(substr($profile->FullName ?? 'U', 0, 1)) : 'U' }}
            </div>
        @endif
        
        <div class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <span class="text-white text-sm font-medium">Change Photo</span>
        </div>
        
        <input type="file" name="{{ $name }}" id="{{ $name }}" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
    </div>
    
    <p class="text-sm text-gray-500">Click to upload a profile photo</p>
    <p class="text-xs text-gray-400" id="selected-file-name">No file selected</p>
    
    @error($name)
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('{{ $name }}');
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
                    img.className = 'h-full w-full object-cover';
                    previewContainer.appendChild(img);
                    
                    // Show toast notification
                    if (typeof showCustomToast === 'function') {
                        showCustomToast('Photo selected! Click save to update your profile.', 'info');
                    }
                };
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
