<header class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold">
            Event <span class="text-indigo-600">Planner</span>
        </a>

        <div class="flex items-center gap-6 text-sm">
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.categories.index') }}"
                       class="{{ request()->routeIs('admin.categories.*') ? 'text-indigo-600 font-semibold underline underline-offset-4' : 'text-gray-700 hover:text-indigo-600' }}">
                        Categories
                    </a>

                    <a href="{{ route('admin.events.index') }}"
                       class="{{ request()->routeIs('admin.events.*') ? 'text-indigo-600 font-semibold underline underline-offset-4' : 'text-gray-700 hover:text-indigo-600' }}">
                        Events
                    </a>

                    <a href="{{ route('admin.registrations.index') }}"
                       class="{{ request()->routeIs('admin.registrations.*') ? 'text-indigo-600 font-semibold underline underline-offset-4' : 'text-gray-700 hover:text-indigo-600' }}">
                        Registrations
                    </a>
                @endif

                {{-- Profile dropdown (same one you wanted) --}}
                <div x-data="{ open:false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-3">
                        <img
                            src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=EEF2FF&color=4F46E5"
                            class="w-9 h-9 rounded-full"
                            alt="avatar"
                        >
                        <div class="leading-tight text-right">
                            <div class="font-semibold">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                        </div>
                        <svg class="w-4 h-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 10.94l3.71-3.71a.75.75 0 1 1 1.06 1.06l-4.24 4.24a.75.75 0 0 1-1.06 0L5.21 8.29a.75.75 0 0 1 .02-1.08z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open=false" x-transition
                         class="absolute right-0 mt-3 w-64 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden z-50">
                        <div class="p-4 flex items-center gap-3">
                            <img
                                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=EEF2FF&color=4F46E5"
                                class="w-11 h-11 rounded-full"
                                alt="avatar"
                            >
                            <div>
                                <div class="font-semibold text-gray-900">{{ auth()->user()->name }}</div>
                                <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
                            </div>
                        </div>

                        <div class="border-t border-gray-100"></div>

                        <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-sm hover:bg-gray-50">
                            View profile
                        </a>

                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.registrations.index') }}" class="block px-4 py-3 text-sm hover:bg-gray-50">
                                Registrations
                            </a>
                        @endif

                        <div class="border-t border-gray-100"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-4 py-3 text-sm hover:bg-gray-50">
                                Log out
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600">Login</a>
                <a href="{{ route('register') }}" class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                    Signup
                </a>
            @endauth
        </div>
    </div>
</header>
