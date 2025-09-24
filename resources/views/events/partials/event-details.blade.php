<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-6">
        {{-- Header (Event Name / Organiser) --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2"> {{$event->title}}</h1>
            <p class="text-lg text-gray-600"> Organised by {{$event->organiser->name}}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {{-- Date & Time --}}
            <div class="flex items-start space-x-3">
                <x-lucide-calendar class="w-6 h-6 text-blue-500 mt-1" />
                <div>
                    <h3 class="font-semibold text-gray-900">Date & Time</h3>
                    <p class="text-gray-600">{{$event->start_time->format('l, F j, Y')}}</p>
                    <p class="text-gray-600">{{$event->start_time->format('g:i A')}} - {{ $event->end_time->format('g:i A') }}</p>
                </div>
            </div>

            {{-- Location --}}
            <div class="flex items-start space-x-3">
                <x-lucide-map-pin class="w-6 h-6 text-blue-500 mt-1" />
                <div>
                    <h3 class="font-semibold text-gray-900">Location</h3>
                    <p class="text-gray-600">{{ $event->location }}</p>
                </div>
            </div>

            {{-- Attendees --}}
            <div class="flex items-start space-x-3">
                <x-lucide-users class="w-6 h-6 text-blue-500 mt-1" />
                <div>
                    <h3 class="font-semibold text-gray-900">Attendance</h3>
                    <p class="text-gray-600">{{ $event->bookings->count() }} / {{ $event->capacity }} attendees</p>
                    @if($event->availableSpaces() > 0)
                        <p class="text-green-600 text-sm">{{ $event->availableSpaces() }} spots available</p>
                    @else
                        <p class="text-red-600 text-sm">Event is full</p>
                    @endif
                </div>
            </div>

            {{-- Status --}}
            <div class="flex items-start space-x-3">
                <x-lucide-check-circle class="w-6 h-6 text-blue-500 mt-1" />
                <div>
                    <h3 class="font-semibold text-gray-900">Status</h3>
                    @if(!$event->hasStarted())
                        <p class="text-green-600">Upcoming Event</p>
                    @else
                        <p class="text-gray-600">Past Event</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="border-t pt-6">
            <h3 class="font-semibold text-gray-900 mb-3">About This Event</h3>
            <div class="prose max-w-none text-gray-600">
                {!! nl2br(e($event->description)) !!}
            </div>
        </div>
    </div>
</div>