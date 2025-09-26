<div class="space-y-2 text-sm text-gray-500">
    <!-- Date & Time -->
    <div class="flex items-center">
        <x-lucide-calendar class="w-4 h-4 mr-2" />
        {{ $event->start_time->format('M j, Y \a\t g:i A') }}
    </div>

    <!-- Location -->
    <div class="flex items-center">
        <x-lucide-map-pin class="w-4 h-4 mr-2" />
        {{ $event->location }}
    </div>

    <!-- Organizer -->
    <div class="flex items-center">
        <x-lucide-user class="w-4 h-4 mr-2" />
        Organized by {{ $event->organiser->name }}
    </div>
</div>