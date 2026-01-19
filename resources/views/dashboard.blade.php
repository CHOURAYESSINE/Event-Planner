<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Event Planner</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">

<!-- NAVBAR -->
<header class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <a href="{{ route('home') }}" class="text-xl font-bold">
            Event <span class="text-indigo-600">Planner</span>
        </a>

        <div class="flex items-center gap-6 text-sm">
            {{-- ADMIN NAV BUTTONS (only admin sees them) --}}
            @auth
                @if(auth()->user()->role === 'admin')
                  <a href="{{ route('admin.categories.index') }}">Categories</a>
                    <a href="{{ route('admin.events.index') }}">Events</a>

                @endif
            @endauth

            {{-- PROFILE (everyone logged in) --}}
            <div class="flex items-center gap-6 text-sm">
        

                <div x-data="{ open: false }" class="relative">

            <!-- Profile Button -->
            <button @click="open = !open"
                    class="flex items-center gap-3 focus:outline-none">

                <img
                    src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=EEF2FF&color=4F46E5"
                    class="w-9 h-9 rounded-full"
                    alt="avatar"
                >

                <div class="leading-tight text-right">
                    <div class="font-semibold text-gray-800">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                </div>
            </button>

            <!-- Dropdown -->
            <div x-show="open"
                @click.away="open = false"
                x-transition
                class="absolute right-0 mt-3 w-56 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden z-50">

                <!-- User Info -->
                <div class="flex items-center px-4 py-3 border-b">
                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=EEF2FF&color=4F46E5"
                        class="w-10 h-10 rounded-full mr-3"
                        alt="avatar"
                    >

                    <div>
                        <div class="text-sm font-semibold text-gray-900">
                            {{ auth()->user()->name }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ auth()->user()->email }}
                        </div>
                    </div>
                </div>

                <!-- Links -->
                <div class="py-1">
                    <a href="{{ route('profile.show') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        View profile
                    </a>

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.registrations.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Registrations
                        </a>

                    @endif
                </div>


                <!-- Logout -->
                <div class="border-t">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            Log out
                        </button>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
</header>


<!-- HERO -->
<section class="max-w-7xl mx-auto px-6 pt-6">
    <div class="rounded-2xl overflow-hidden shadow-lg relative">
        <img src="{{ asset('images/home/hero.jpg') }}"
             class="w-full h-[260px] md:h-[320px] object-cover"
             alt="hero">
        <div class="absolute inset-0 bg-black/40"></div>

        <div class="absolute inset-0 flex items-center justify-center text-center px-6">
            <h1 class="text-white text-3xl md:text-5xl font-extrabold leading-tight">
                MADE FOR THOSE<br class="hidden md:block"> WHO DO
            </h1>
        </div>

        <button class="absolute left-4 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/20 text-white">
            ‹
        </button>
        <button class="absolute right-4 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/20 text-white">
            ›
        </button>
    </div>
</section>

<!-- UPCOMING EVENTS -->
<section class="max-w-7xl mx-auto px-6 py-10">
    <div class="flex items-center justify-between gap-4 flex-wrap">
        <h2 class="text-xl font-bold">
            Upcoming <span class="text-indigo-600">Events</span>
        </h2>

        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-3 flex-wrap">
            <div class="relative">
                <input
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search"
                    class="h-10 w-48 rounded-md border border-gray-200 bg-white pl-3 pr-9 text-sm
                           focus:border-indigo-500 focus:ring-indigo-500"
                >
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
            </div>

            <select class="h-10 rounded-md border border-gray-200 bg-white px-3 text-sm">
                <option>Weekend</option>
            </select>

            <select class="h-10 rounded-md border border-gray-200 bg-white px-3 text-sm">
                <option>Any category</option>
            </select>
        </form>
    </div>

    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($events as $event)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                <div class="relative">
                    <img
                        src="{{ $event->image ? asset('storage/'.$event->image) : asset('images/home/event-placeholder.jpg') }}"
                        class="w-full h-40 object-cover"
                        alt="event"
                    >

                    @if((int)$event->is_free === 1)
                        <span class="absolute top-3 left-3 text-[11px] px-2 py-1 rounded bg-indigo-600 text-white">
                            FREE
                        </span>
                    @else
                        <span class="absolute top-3 left-3 text-[11px] px-2 py-1 rounded bg-indigo-600 text-white">
                            {{ number_format((float)$event->price, 2) }} €
                        </span>
                    @endif
                </div>

                <div class="p-4">
                    <h3 class="font-semibold text-sm line-clamp-2">{{ $event->title }}</h3>

                    <p class="text-xs text-gray-500 mt-2">
                        {{ \Carbon\Carbon::parse($event->start_date)->format('D, M j, Y') }}
                    </p>

                    <p class="text-xs text-gray-400 mt-1">
                        {{ $event->place }}
                    </p>

                    <a href="{{ route('events.show', $event->id) }}"
                       class="inline-block mt-3 text-indigo-600 text-sm font-semibold hover:underline">
                        View details
                    </a>
                </div>
            </div>
        @empty
            <div class="text-gray-600">
                No upcoming events.
            </div>
        @endforelse
    </div>

    <div class="mt-8 flex justify-center">
        {{ $events->links() }}
    </div>
</section>

<!-- FOOTER -->
<footer class="bg-indigo-900 text-white">
    <div class="max-w-7xl mx-auto px-6 py-10 text-center">
        <div class="text-xl font-bold mb-4">
            Event <span class="text-indigo-300">Planner</span>
        </div>

        <form class="flex items-center justify-center gap-2 mb-6">
            <input class="h-10 w-64 rounded-md px-3 text-sm text-gray-900"
                   placeholder="Enter your email">
            <button class="h-10 px-4 rounded-md bg-indigo-600 hover:bg-indigo-700 transition">
                Subscribe
            </button>
        </form>

        <div class="flex items-center justify-center gap-6 text-sm text-white/80 mb-6">
            <a href="{{ route('home') }}" class="hover:text-white">Home</a>
            <a href="{{ route('dashboard') }}" class="hover:text-white">Dashboard</a>
        </div>

        <div class="text-xs text-white/60">
            Non Copyrighted © {{ date('Y') }} Event Planner
        </div>
    </div>
</footer>

</body>
</html>
