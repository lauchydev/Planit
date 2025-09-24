<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800">
                        {{ config('app.name', 'PlanIt') }}
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @include('layouts.nav-links')
                </div>
            </div>

            <!-- Settings Dropdown / Auth Links -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    @include('layouts.user-dropdown')
                @else
                    @include('layouts.guest-links')
                @endauth
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        @include('layouts.responsive-nav')
    </div>
</nav>
