<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Organiser Dashboard') }}
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

                    <div class="mb-4 p-4 rounded border border-slate-200 bg-slate-50 text-slate-700">
                        <p class="text-sm">
                            Manage your events here
                        </p>
                        <p class="text-sm">
                            Use <strong>Edit</strong> to update details. <strong>Delete</strong> is disabled when an event has bookings.
                        </p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Starts</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ends</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Booked</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Remaining</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Fullness</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($rows as $row)
                                    <tr>
                                        <td class="px-3 py-2 text-sm text-gray-900">
                                            <a class="text-indigo-600 hover:underline" href="{{ route('events.details', ['event' => $row->event_id]) }}">{{ $row->title }}</a>
                                        </td>
                                        <td class="px-3 py-2 text-sm text-gray-700">{{ \Illuminate\Support\Carbon::parse($row->start_time)->format('M j, Y H:i') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-700">{{ \Illuminate\Support\Carbon::parse($row->end_time)->format('M j, Y H:i') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-700 text-right">{{ $row->capacity }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-700 text-right">{{ $row->bookings_count }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-700 text-right">{{ $row->remaining }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-700 text-right">{{ $row->fullness_percent }}%</td>
                                        <td class="px-3 py-2 text-sm">
                                            @php
                                                $badge = match($row->status) {
                                                    'Full' => 'bg-yellow-100 text-yellow-800',
                                                    'Past' => 'bg-gray-100 text-gray-800',
                                                    default => 'bg-green-100 text-green-800',
                                                };
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badge }}">
                                                {{ $row->status }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 text-sm text-right">
                                            <div class="inline-flex items-center gap-2">
                                                <a href="{{ route('events.update', ['event' => $row->event_id]) }}" class="bg-indigo-500 text-white px-3 py-1 rounded text-sm hover:bg-indigo-600">Edit</a>
                                                @if((int)$row->bookings_count === 0)
                                                    <form action="{{ route('events.delete', ['event' => $row->event_id]) }}" method="POST" onsubmit="return confirm('Delete this event?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Delete</button>
                                                    </form>
                                                @else
                                                    <button disabled type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 disabled:bg-slate-300">Delete</button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-3 py-6 text-center text-gray-500">No events found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
