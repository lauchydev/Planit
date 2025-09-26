<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
    <div class="p-6">
        <!-- Event Header -->
        <div class="flex justify-between items-start mb-3">
            <h3 class="text-lg font-semibold text-gray-900">
                <a href="{{ route('events.details', $event) }}" class="hover:text-indigo-600">
                    {{ $event->title }}
                </a>
            </h3>
                @if ($event->hasStarted())
                    <span class="text-sm text-red-500"> CLOSED </span>
                @elseif ($event->isFull())
                    <span class="text-sm text-red-500"> FULL </span>
                @else
                <span class="text-sm text-gray-500"> {{ $event->availableSpaces() }} spots left </span>
                @endif
            </span>
        </div>

        <!-- Event Description -->
        <p class="text-gray-600 text-sm mb-3 line-clamp-3">
            {{ Str::limit($event->description, 120) }}
        </p>

        {{-- Show event meta info --}}
        @include('events.partials.event-meta', ['event' => $event])

        @if(isset($event->tags) && $event->tags->isNotEmpty())
            <p class="mt-4 text-xs text-blue-500">
            @foreach($event->tags as $tag)
                <span class="inline-block bg-blue-100 px-2 py-0.5 rounded mr-1">{{ $tag->name }}</span>
            @endforeach
            </p>
        @endif

        {{-- View Event Details --}}
        <div class="mt-4">
            <a href="{{ route('events.details', $event) }}" 
               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                View Details
            </a>
        </div>
    </div>
</div>