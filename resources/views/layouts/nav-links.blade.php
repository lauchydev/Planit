<x-nav-link :href="route('home')" :active="request()->routeIs('home')">
    {{ __('Events') }}
</x-nav-link>

@auth
    @if(auth()->user()->isAttendee())
{{--         <x-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">
            {{ __('My Bookings') }}
        </x-nav-link> --}}
    @endif

    @if(auth()->user()->isOrganiser())
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
        </x-nav-link>
{{--         <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
            {{ __('My Events') }}
        </x-nav-link> --}}
    @endif
@endauth