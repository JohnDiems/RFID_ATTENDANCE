<div class="bg-white rounded-lg shadow p-6 border-l-4 border-{{ $color ?? 'violet' }}-500">
    <h3 class="text-gray-500 text-sm font-medium">{{ $title }}</h3>
    <div class="flex items-center">
        <div class="text-3xl font-bold text-gray-700">{{ $value }}</div>
        @if(isset($badge))
            <div class="ml-2 flex-shrink-0 flex">
                <div class="px-2 py-1 text-xs rounded-full bg-{{ $badgeColor ?? 'violet' }}-100 text-{{ $badgeColor ?? 'violet' }}-800">
                    {{ $badge }}
                </div>
            </div>
        @endif
    </div>
</div>
