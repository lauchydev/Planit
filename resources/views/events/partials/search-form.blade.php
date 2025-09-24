<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-6">
        <form method="GET" action="{{ route('home') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" 
                           name="search" 
                           id="search"
                           value="{{ request('search') }}"
                           placeholder="Event title, description, or location..."
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <!-- Date From -->
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700">From Date</label>
                    <input type="date" 
                           name="date_from" 
                           id="date_from"
                           value="{{ request('date_from') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <!-- Date To -->
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700">To Date</label>
                    <input type="date" 
                           name="date_to" 
                           id="date_to"
                           value="{{ request('date_to') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                    <input type="text" 
                           name="location" 
                           id="location"
                           value="{{ request('location') }}"
                           placeholder="Filter by location..."
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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

            <div class="flex gap-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Search Events
                </button>
                <a href="{{ route('home') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>
</div>