<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $title ?? 'Recent Activity' }}</h3>
    <div class="space-y-4">
        @forelse($activities as $activity)
        <div class="flex items-start">
            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-violet-100 flex items-center justify-center">
                @if($activity['type'] == 'attendance')
                    <svg class="h-5 w-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                @else
                    <svg class="h-5 w-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                @endif
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-gray-900">
                    {{ $activity['profile']->FullName ?? 'Unknown' }}
                </p>
                <p class="text-sm text-gray-500">
                    {{ $activity['action'] }} - {{ $activity['time']->format('h:i A') }}
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity['status'] == 'late' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        {{ ucfirst($activity['status']) }}
                    </span>
                </p>
            </div>
        </div>
        @empty
        <div class="text-center py-4 text-gray-500">
            No recent activity found.
        </div>
        @endforelse
    </div>
</div>
