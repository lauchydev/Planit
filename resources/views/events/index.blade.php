<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upcoming Events') }}
        </h2>
    </x-slot>

        <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        {{-- Filer/Search Area --}}
                        @include('events.partials.search-form')

                        {{-- AJAX Results --}}
                        <div id="events-container">
                                @include('events.partials.list', ['events' => $events])
                        </div>
                </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('form[action="{{ route('events.index') }}"]') || document.querySelector('#filters') || document.querySelector('form');
            const container = document.getElementById('events-container');

            if (!form || !container) return;

            const update = (urlParams) => {
                const params = urlParams || new URLSearchParams(new FormData(form));
                fetch(`{{ route('events.filter') }}?` + params.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => {
                    container.innerHTML = data.html;
                }).catch(() => {});
            };

            /* Event delegation for pagination */
            container.addEventListener('click', (e) => {
                const link = e.target.closest('a');
                if (!link || !link.href) return;
                if (link.href.includes('page=')) {
                    e.preventDefault();
                    const url = new URL(link.href, window.location.origin);
                    const params = new URLSearchParams(url.search);
                    const current = new URLSearchParams(new FormData(form));
                    current.forEach((v,k) => { if (k !== 'page') params.set(k, v); });
                    update(params);
                }
            });

            /* Stop submit reload and AJAX refresh (so much better) */
            form.addEventListener('submit', (e) => { e.preventDefault(); update(); });

            /* Stop search submit reload and dynamically show search filter without refresh (also awesome) */
            form.addEventListener('change', () => update());
            const searchInput = form.querySelector('input[name="search"]');
            if (searchInput) {
                const debounce = (fn, wait) => { let t; return (...args)=>{ clearTimeout(t); t=setTimeout(()=>fn.apply(null,args), wait); }; };
                searchInput.addEventListener('input', debounce(() => update(), 300));
            }
        });
        </script>
</x-app-layout>