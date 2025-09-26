<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
  @forelse ($events as $event)
    @include('events.partials.event-card', ['event' => $event])
  @empty
    <div class="col-span-full text-center text-gray-500 py-8">No events match your filters.</div>
  @endforelse
</div>

<div class="mt-4">
  {{ $events->links() }}
</div>