@csrf
<div class="space-y-5">
    <div>
        <label class="block text-sm font-medium text-gray-700">Title</label>
        <input name="title" maxlength="100" required
               value="{{ old('title', $event->title ?? '') }}"
               class="mt-1 w-full border-gray-300 rounded-md"/>
        <x-input-error :messages="$errors->get('title')" class="mt-1"/>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" rows="5" required
                  class="mt-1 w-full border-gray-300 rounded-md">{{ old('description', $event->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-1"/>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Start Time</label>
            <input type="datetime-local" name="start_time" required
                   value="{{ old('start_time', isset($event)? $event->start_time->format('Y-m-d\TH:i') : '') }}"
                   class="mt-1 w-full border-gray-300 rounded-md"/>
            <x-input-error :messages="$errors->get('start_time')" class="mt-1"/>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">End Time</label>
            <input type="datetime-local" name="end_time" required
                   value="{{ old('end_time', isset($event)? $event->end_time->format('Y-m-d\TH:i') : '') }}"
                   class="mt-1 w-full border-gray-300 rounded-md"/>
            <x-input-error :messages="$errors->get('end_time')" class="mt-1"/>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Location</label>
        <input name="location" required
               value="{{ old('location', $event->location ?? '') }}"
               class="mt-1 w-full border-gray-300 rounded-md"/>
        <x-input-error :messages="$errors->get('location')" class="mt-1"/>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Capacity</label>
        <input type="number" name="capacity" min="1" max="1000" required
               value="{{ old('capacity', $event->capacity ?? 50) }}"
               class="mt-1 w-full border-gray-300 rounded-md"/>
        <x-input-error :messages="$errors->get('capacity')" class="mt-1"/>
    </div>

    <div class="pt-2 flex flex-wrap items-center gap-3">

        {{-- Update Button --}}
        <button 
         type="submit" 
         class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-md text-sm font-medium shadow focus:outline-none focus:ring-2 focus:ring-indigo-500"
        >
            <x-lucide-check class="w-4 h-4" />
            Update Event
        </button>

        {{-- Cancel Button --}}
        <a href="{{ isset($event) ? route('events.details', $event) : route('home') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-md text-sm font-medium border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <x-lucide-arrow-left class="w-4 h-4" />
            Cancel
        </a>


        @isset($event)
        {{-- Delete Button --}}
            <button 
             type="submit" 
             form="delete-event"
             class="inline-flex items-center gap-2 px-4 py-2.5 rounded-md text-sm font-medium bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 shadow"
            >
                <x-lucide-trash-2 class="w-4 h-4" />
                Delete
            </button>
        @endisset
    </div>
</div>