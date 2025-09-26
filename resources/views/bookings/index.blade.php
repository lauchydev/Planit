<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Bookings') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 p-3 rounded bg-red-100 text-red-800">{{ session('error') }}</div>
                    @endif

                    @if($bookings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Starts</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ends</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booked At</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($bookings as $booking)
                                        <tr>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                <a href="{{ route('events.details', $booking->event) }}" class="text-indigo-600 hover:underline">
                                                    {{ $booking->event->title }}
                                                </a>
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-700">{{ $booking->event->start_time->format('M j, Y H:i') }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-700">{{ $booking->event->end_time->format('M j, Y H:i') }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-700">{{ $booking->event->location }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-700">{{ $booking->booked_at->format('M j, Y H:i') }}</td>
                                            <td class="px-3 py-2 text-sm text-right">
                                                @if(!$booking->event->hasStarted())
                                                    <form action="{{ route('events.bookings.delete', [$booking->event, $booking]) }}" method="POST" onsubmit="return confirm('Cancel this booking?');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Cancel</button>
                                                    </form>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Event started</span>
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
                        <div class="text-center text-gray-500 py-6">
                            <p>You haven't booked any events yet!</p>
                            <p class="pt-2"><a href="./" class="text-blue-500 underline hover:text-blue-600 hover:font-bold">Book an Event</a></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
