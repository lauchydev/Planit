@auth
    @if(auth()->user()->isAttendee())
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="font-semibold text-lg text-gray-900 mb-4">Book This Event</h3>
                
                @php
                    $userBooking = auth()->user()->bookings()->where('event_id', $event->id)->first();
                @endphp

                @if($userBooking)
                    {{-- Booked Event --}}
                    <div class="bg-green-50 border border-green-200 rounded-md p-4">
                        <div class="flex">
                            <x-lucide-check-circle class="h-5 w-5 text-green-400" />
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">You're registered!</h3>
                                <div class="mt-2 text-sm text-green-700">
                                    <p>You booked this event on {{ $userBooking->booked_at->format('M j, Y \a\t g:i A') }}</p>
                                </div>
                                <div class="mt-4">
                                    @if(!$event->hasStarted())
                                        <form action="{{ route('events.bookings.delete', [$event, $userBooking]) }}" method="POST" onsubmit="return confirm('Cancel your booking?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md text-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                Cancel Booking
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($event->hasStarted())
                    {{-- Finished Event --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
                        <p class="text-gray-600">This event has already occurred.</p>
                    </div>
                @elseif($event->isFull())
                    {{-- Full Event --}}
                    <div class="bg-red-50 border border-red-200 rounded-md p-4">
                        <p class="text-red-700">This event is full. No more spots available.</p>
                    </div>
                @else
                    {{-- Book Event --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <p class="text-blue-700 mb-4">{{ $event->availableSpaces() }} spots remaining</p>
                        <form action="{{ route('events.book', $event) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Book This Event
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @endif
@else
    {{-- Guest --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
            <h3 class="font-semibold text-lg text-gray-900 mb-4">Book This Event</h3>
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                <p class="text-yellow-700">
                    <a href="{{ route('login') }}" class="font-medium underline">Login</a> or 
                    <a href="{{ route('register') }}" class="font-medium underline">Register</a> 
                    to book this event.
                </p>
            </div>
        </div>
    </div>
@endauth