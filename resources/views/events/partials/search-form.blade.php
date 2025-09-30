<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-6">
        <form method="GET" action="{{ route('events.index') }}" id="filters" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search (title/location/description) -->
                <div class="lg:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" 
                           name="search" 
                           id="search"
                           value="{{ request('search') }}"
                           placeholder="Search by title, description or location..."
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <!-- Organized by -->
                <div>
                    <label for="organiser_id" class="block text-sm font-medium text-gray-700">Organized by</label>
                    <select name="organiser_id" id="organiser_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Any organiser</option>
                        @isset($organisers)
                            @foreach($organisers as $org)
                                <option value="{{ $org->id }}" @selected((string)request('organiser_id') === (string)$org->id)>{{ $org->name }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>

                <!-- Scope -->
                <div>
                    <label for="scope" class="block text-sm font-medium text-gray-700">Scope</label>
                    <select name="scope" id="scope" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="" @selected(request('scope')===null || request('scope')==='')>Upcoming</option>
                        <option value="past" @selected(request('scope')==='past')>Past</option>
                        <option value="all" @selected(request('scope')==='all')>All</option>
                    </select>
                </div>
            </div>

            <!-- Tags (advanced req) -->
            @isset($tags)
            <div class="flex flex-wrap gap-3 items-center">
                <span class="text-sm font-medium text-gray-700">Tags:</span>
                @foreach($tags as $tag)
                    <label class="inline-flex items-center gap-1 text-sm">
                        <input class="rounded-md cursor-pointer" type="checkbox" name="tags[]" value="{{ $tag->id }}" @checked(collect(request('tags', []))->contains($tag->id)) />
                        <span>{{ $tag->name }}</span>
                    </label>
                @endforeach
            </div>
            <!-- Add radio buttons for filter mode -->
            <div class="flex gap-4 items-center">
                <span class="text-sm font-medium text-gray-700">Match:</span>
                <label class="inline-flex items-center">
                    <input type="radio" name="tag_match" value="any" 
                        @checked(request('tag_match', 'any') === 'any') />
                    <span class="ml-1">Any selected tag</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="tag_match" value="all" 
                        @checked(request('tag_match') === 'all') />
                    <span class="ml-1">All selected tags</span>
                </label>
            </div>
            @endisset

            <div class="flex gap-2">
                <a href="{{ route('events.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>
</div>