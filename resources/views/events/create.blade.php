<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800">Create Event</h2>
	</x-slot>
	<div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
		<div class="bg-white p-6 shadow-sm rounded-lg">
			<form method="POST" action="{{ route('events.handleCreate') }}">
				@include('events.partials.form', ['submitLabel' => 'Create Event'])
			</form>
		</div>
	</div>
</x-app-layout>
