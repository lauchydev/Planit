<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Events') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            @if($events->count() > 0)
                <div class="overflow-x-auto bg-white shadow rounded">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left">Title</th>
                                <th class="px-4 py-2 text-left">Start</th>
                                <th class="px-4 py-2 text-left">End</th>
                                <th class="px-4 py-2 text-left">Bookings</th>
                                <th class="px-4 py-2 text-left">Capacity</th>
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($events as $event)
                            <tr class="border-b">
                                <td class="px-4 py-2">
                                    <a href="{{ route('events.details', $event) }}" class="text-blue-600 hover:underline">{{ $event->title }}</a>
                                </td>
                                <td class="px-4 py-2">{{ $event->start_time->format('M j, Y g:i A') }}</td>
                                <td class="px-4 py-2">{{ $event->end_time->format('M j, Y g:i A') }}</td>
                                <td class="px-4 py-2">{{ $event->bookings_count }}</td>
                                <td class="px-4 py-2">{{ $event->capacity }}</td>
                                <td class="px-4 py-2">
                                    @if($event->hasStarted())
                                        <span class="text-gray-500 text-xs">Started</span>
                                    @else
                                        <span class="text-green-600 text-xs">Upcoming</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 flex gap-2">
                                    <a href="{{ route('events.update', $event) }}" class="bg-indigo-500 text-white px-3 py-1 rounded text-sm hover:bg-indigo-600">Edit</a>
                                    @if(!$event->bookings()->exists())
                                        <form action="{{ route('events.delete', $event) }}" method="POST" onsubmit="return confirm('Delete this event?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Delete</button>
                                        </form>
                                    @else
                                        <span class="text-gray-300 text-xs">Lock</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $events->links() }}
                </div>
            @else
                <div class="bg-white p-8 rounded shadow text-center text-gray-500">You have not created any events yet.</div>
            @endif
        </div>
    </div>
</x-app-layout>
