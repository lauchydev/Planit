<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upcoming Events') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter Form -->
            @include('events.partials.search-form')

            <!-- Events Grid or Empty State -->
            @if($events->count() > 0)
                @include('events.partials.events-grid', ['events' => $events])
                @include('events.partials.pagination', ['events' => $events])
            @else
                @include('events.partials.empty-state')
            @endif
        </div>
    </div>
</x-app-layout>