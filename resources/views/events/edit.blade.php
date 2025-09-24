<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800">Edit Event</h2>
	</x-slot>
	<div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        {{-- Update event button logic --}}
		<div class="bg-white p-6 shadow-sm rounded-lg">
			<form method="POST" action="{{ route('events.handleUpdate', $event) }}" class="space-y-6">
				@method('PUT')
				@include('events.partials.form', ['event' => $event])
			</form>
		</div>

        {{-- Delete event button logic --}}
		<form id="delete-event" method="POST" action="{{ route('events.delete', $event) }}" onsubmit="return confirm('Delete this event?')" class="hidden">
			@csrf
			@method('DELETE')
		</form>
	</div>
</x-app-layout>
