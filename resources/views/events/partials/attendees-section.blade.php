@auth
    @php
        $canViewAttendees = auth()->user()->isOrganiser() && auth()->user()->id === $event->organiser_id;
        $userHasBooked = auth()->user()->bookings()->where('event_id', $event->id)->exists();
    @endphp

    @if($canViewAttendees || $userHasBooked)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="font-semibold text-lg text-gray-900 mb-4">
                    Attendees ({{ $event->bookings->count() }})
                </h3>

                @if($event->bookings->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($event->bookings as $booking)
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                {{-- User Picture (first letter intitial) --}}
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 bg-indigo-600 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">
                                            {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    {{-- Username --}}
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $booking->user->name }}
                                    </p>
                                    {{-- Booked at --}}
                                    <p class="text-xs text-gray-500">
                                        Booked {{ $booking->booked_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No attendees yet.</p>
                @endif
            </div>
        </div>
    @endif
@endauth