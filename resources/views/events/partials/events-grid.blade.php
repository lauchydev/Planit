<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    @foreach($events as $event)
        @include('events.partials.event-card', ['event' => $event])
    @endforeach
</div>