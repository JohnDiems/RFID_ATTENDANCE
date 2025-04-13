<div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
    <div class="md:flex">
        <div class="md:flex-shrink-0">
            <img class="h-48 w-full object-cover md:w-48" src="{{ $profile->photo ? asset('storage/' . $profile->photo) : asset('images/default-avatar.png') }}" alt="{{ $profile->FullName }}">
        </div>
        <div class="p-8">
            <div class="uppercase tracking-wide text-sm text-violet-600 font-semibold">{{ $profile->StudentRFID }}</div>
            <h2 class="mt-1 text-2xl font-semibold text-gray-900">{{ $profile->FullName }}</h2>
            <div class="mt-2 text-gray-600">
                <p><span class="font-medium">Year Level:</span> {{ $profile->YearLevel }}</p>
                <p><span class="font-medium">Course:</span> {{ $profile->Course }}</p>
                <p><span class="font-medium">Section:</span> {{ $profile->Section }}</p>
            </div>
            <div class="mt-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $profile->Status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $profile->Status ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
    </div>
</div>
