<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $event->title }}</h2>
            <a href="{{ route('events.index') }}" class="text-indigo-600 hover:text-indigo-800"> Back to Events</a>
        </div>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Event Details --}}
            @include('events.partials.event-details', ['event' => $event])

            {{-- Booking Section --}}
            @include('events.partials.booking-section', ['event' => $event])

            {{-- Attendee List --}}
            @include('events.partials.attendees-section', ['event' => $event])
        </div>
    </div>

</x-app-layout>
