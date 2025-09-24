<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Bookings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            @if($bookings->count() > 0)
                <div class="overflow-x-auto bg-white shadow rounded">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left">Event</th>
                                <th class="px-4 py-2 text-left">Start</th>
                                <th class="px-4 py-2 text-left">End</th>
                                <th class="px-4 py-2 text-left">Location</th>
                                <th class="px-4 py-2 text-left">Booked At</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($bookings as $booking)
                            <tr class="border-b">
                                <td class="px-4 py-2">
                                    <a href="{{ route('events.details', $booking->event) }}" class="text-blue-600 hover:underline">
                                        {{ $booking->event->title }}
                                    </a>
                                </td>
                                <td class="px-4 py-2">{{ $booking->event->start_time->format('M j, Y g:i A') }}</td>
                                <td class="px-4 py-2">{{ $booking->event->end_time->format('M j, Y g:i A') }}</td>
                                <td class="px-4 py-2">{{ $booking->event->location }}</td>
                                <td class="px-4 py-2">{{ $booking->booked_at->format('M j, Y g:i A') }}</td>
                                <td class="px-4 py-2">
                                    @if(!$booking->event->hasStarted())
                                        <form action="{{ route('events.bookings.delete', [$booking->event, $booking]) }}" method="POST" onsubmit="return confirm('Cancel this booking?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Cancel</button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 text-xs">Event started</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $bookings->links() }}
                </div>
            @else
                <div class="bg-white p-8 rounded shadow text-center text-gray-500">
                    <p>You haven't booked any events yet!</p>
                    <p class="pt-2"><a href="./" class="text-blue-500 underline hover:text-blue-600 hover:font-bold">Book an Event</a></p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
