<x-nav-link :href="route('home')" :active="request()->routeIs('home')">
    {{ __('Discover') }}
</x-nav-link>

@auth
    @if(auth()->user()->isAttendee())
        <x-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">
            {{ __('My Bookings') }}
        </x-nav-link>
    @endif

    @if(auth()->user()->isOrganiser())
        <x-nav-link :href="route('events.create')" :active="request()->routeIs('events.create')">
            {{ __('Create Event') }}
        </x-nav-link>
        <x-nav-link :href="route('events.mine')" :active="request()->routeIs('events.mine')">
            {{ __('My Events') }}
        </x-nav-link>
    @endif
@endauth