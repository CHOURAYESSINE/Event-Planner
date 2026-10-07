<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $event->title }} - Event Planner</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script>
function openBookModal(){ document.getElementById('bookModal').classList.remove('hidden'); }
function closeBookModal(){ document.getElementById('bookModal').classList.add('hidden'); }
</script>

</head>

<body class="bg-white text-gray-900">

{{-- Navbar --}}
<header class="bg-white">
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
                    <a href="#"
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

<main class="max-w-6xl mx-auto px-6 pb-10">

    {{-- HERO SECTION --}}
    @php
        $hero = $event->image
            ? $event->image_url
            : 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&w=1600&q=80';
    @endphp

    <section class="rounded-2xl overflow-hidden shadow-sm border border-gray-100">

        <div class="relative h-72 md:h-80">
            <img src="{{ $hero }}" class="absolute inset-0 w-full h-full object-cover" alt="event image">
            <div class="absolute inset-0 bg-black/40"></div>

            {{-- Back button --}}
            <a href="javascript:history.back()"
   class="absolute top-4 left-0 z-50 inline-flex items-center gap-2 px-3 py-2 rounded-md bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">
    ← Back
</a>

            {{-- Overlay content --}}
            <div class="relative h-full flex items-end">
                <div class="p-6 md:p-10 max-w-xl text-white">
                    <h1 class="text-4xl font-extrabold leading-tight">
                        {{ $event->title }}
                    </h1>

                    <div class="mt-2 text-sm font-semibold opacity-95">
                        {{ $event->place }}
                    </div>

                    <p class="mt-3 text-sm text-white/90 leading-relaxed">
                        {{ \Illuminate\Support\Str::limit(strip_tags($event->description ?? ''), 220) }}
                    </p>

                    <div class="mt-5">
                        @auth
                            @if(auth()->user()->role !== 'admin')

                                @if($alreadyBooked)
                                    {{-- User booked => can always cancel --}}
                                    <form method="POST" action="{{ route('events.unbook', $event) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-gray-200 text-gray-900 px-6 py-2 rounded-lg font-semibold hover:bg-gray-300">
                                            Cancel booking
                                        </button>
                                    </form>

                                @else
                                    {{-- Not booked yet --}}
                                    @if((int)$event->capacity <= 0)
                                        <button disabled
                                                class="bg-gray-100 text-gray-400 px-6 py-2 rounded-lg font-semibold cursor-not-allowed">
                                            Fully booked
                                        </button>
                                    @else
                                        <button type="button"
                                                onclick="openBookModal()"
                                                class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-indigo-700">
                                            Book now
                                        </button>
                                    @endif
                                @endif

                            @endif
                        @else
                            <a href="{{ route('login') }}"
                            class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-indigo-700">
                                Login to book
                            </a>
                        @endauth




                    </div>
                </div>
            </div>
        </div>

        {{-- CONTENT --}}
        <div class="bg-white">

            {{-- DESCRIPTION + HOURS/CAPACITY (exact layout) --}}
            <div class="border-t border-gray-100 bg-white">
                <div class="px-8 py-6">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

                        {{-- LEFT --}}
                        <div class="lg:col-span-7">
                            <h2 class="text-lg font-semibold mb-2">Description</h2>
                            <p class="text-sm text-gray-500 leading-7">
                                {{ $event->description ?? 'No description available.' }}
                            </p>
                        </div>

                        {{-- RIGHT --}}
                        <div class="lg:col-span-5">
                            <div class="flex flex-col gap-10">

                                {{-- Hours --}}
                                <div>
                                    <h3 class="text-lg font-semibold mb-2">Hours</h3>

                                    <div class="text-sm text-gray-500 space-y-2">
                                        <div class="flex items-center gap-3">
                                            <span class="min-w-[110px]">Weekdays hour:</span>
                                            <span class="text-indigo-600 font-semibold">7PM - 10PM</span>
                                        </div>

                                        <div class="flex items-center gap-3">
                                            <span class="min-w-[110px]">Sunday hour:</span>
                                            <span class="text-indigo-600 font-semibold">7PM - 10PM</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Capacity --}}
                                <div>
                                    <h3 class="text-lg font-semibold mb-2">Capacity</h3>

                                    <div class="text-sm text-gray-500 flex items-center gap-3">
                                        <span class="min-w-[110px]">Seats number:</span>
                                        <span class="text-indigo-600 font-semibold">
                                            {{ $event->capacity }} persons
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>


            {{-- OTHER EVENTS --}}
            <div class="px-8 py-10">
                <h2 class="text-lg font-semibold mb-4">Other events you may like</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($otherEvents as $e)
                        @php
                            $img = $e->image ? asset('storage/'.$e->image) : $hero;
                        @endphp

                        <a href="{{ route('events.show', $e) }}"
   class="block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    {{-- Image --}}
    <div class="relative h-44">
        <img src="{{ $img }}" class="w-full h-full object-cover" alt="event">

        {{-- FREE badge --}}
        @if($e->is_free)
            <span class="absolute top-3 left-3 bg-indigo-600 text-white text-[11px] font-semibold px-3 py-1 rounded-md">
                FREE
            </span>
        @endif
    </div>

    {{-- Content --}}
    <div class="p-5">
        <h3 class="text-lg font-semibold text-gray-900">
            {{ $e->title }}
        </h3>

        <div class="mt-2 text-sm text-gray-500">
            {{ \Carbon\Carbon::parse($e->start_date)->format('D, M j, Y') }}
        </div>

        <div class="mt-2 text-sm text-indigo-600">
            {{ $e->place }}
        </div>

        <div class="mt-4 text-indigo-600 font-semibold">
            View details
        </div>
    </div>
</a>

                    @empty
                        <p class="text-sm text-gray-500">No other events.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </section>
</main>

{{-- FOOTER --}}
<footer class="bg-indigo-900 text-white mt-10">
    <div class="max-w-6xl mx-auto px-6 py-10 text-center">
        <div class="text-xl font-bold">Event Planner</div>

        <div class="mt-4 flex items-center justify-center gap-2">
            <input class="w-64 max-w-full rounded-md px-3 py-2 text-gray-900" placeholder="Enter your email">
            <button class="px-4 py-2 rounded-md bg-indigo-600 hover:bg-indigo-700 font-semibold">Subscribe</button>
        </div>

        <div class="mt-6 text-sm text-indigo-200 flex items-center justify-center gap-6">
            <a href="{{ route('home') }}" class="hover:text-white">Home</a>
            <a href="{{ route('register') }}" class="hover:text-white">Sign Up</a>
            <a href="{{ route('login') }}" class="hover:text-white">Sign In</a>
        </div>

        <div class="mt-6 text-xs text-indigo-200">
            Non Copyrighted © 2025 Event Planner
        </div>
    </div>
</footer>

</body>

<div id="bookModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/60" onclick="closeBookModal()"></div>

    <div class="relative h-full flex items-center justify-center px-4">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-8">
            <h3 class="text-center font-semibold text-lg mb-6">Book Event</h3>

            <div class="flex justify-center gap-4">
                <button type="button"
                        onclick="closeBookModal()"
                        class="px-6 py-2 rounded-md border border-indigo-600 text-indigo-600 font-semibold hover:bg-indigo-50">
                    Cancel
                </button>

                <form method="POST" action="{{ route('events.book', $event) }}">
                    @csrf
                    <button class="px-6 py-2 rounded-md bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                        Book now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


</html>

