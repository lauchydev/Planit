<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No events found</h3>
        <p class="mt-1 text-sm text-gray-500">
            @if(request()->hasAny(['search', 'date_from', 'date_to', 'location']))
                Try adjusting your search criteria.
            @else
                There are no upcoming events at the moment.
            @endif
        </p>
    </div>
</div>