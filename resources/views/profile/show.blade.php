<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Profile</title>

    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">

    {{-- HEADER / NAVBAR --}}
    @include('partials.header')

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="max-w-6xl mx-auto mt-4 px-6">
            <div class="rounded-md bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-6xl mx-auto mt-4 px-6">
            <div class="rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- MAIN CONTENT --}}
    <main class="max-w-6xl mx-auto px-6 py-10">

        {{-- Page title --}}
        <h1 class="text-2xl font-bold text-indigo-600 mb-8">
            My Profile
        </h1>

        {{-- Profile Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-6">

            {{-- Avatar --}}
            <img
                src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=EEF2FF&color=4F46E5"
                class="w-20 h-20 rounded-full"
                alt="avatar"
            >

            {{-- User Info --}}
            <div>
                <div class="text-xl font-semibold text-gray-900">
                    {{ $user->name }}
                </div>

                <div class="text-sm text-gray-500 mt-1">
                    {{ $user->email }}
                </div>

                <div class="text-xs text-gray-400 mt-2">
                    Role:
                    <span class="font-medium capitalize">
                        {{ $user->role }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-8 flex gap-4">
            <a href="{{ route('profile.edit') }}"
               class="inline-flex items-center px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                Edit profile
            </a>

            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center px-5 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-semibold hover:bg-gray-200 transition">
                Back to dashboard
            </a>
        </div>

        {{-- BOOKED EVENTS LIST --}}
        <section class="mt-12">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900">My booked events</h2>
                <span class="text-sm text-gray-500">
                    {{ isset($registrations) ? $registrations->count() : 0 }} booking(s)
                </span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <div class="text-sm font-semibold text-gray-900">Bookings</div>
                    <div class="text-xs text-gray-500 mt-1">Your current event registrations</div>
                </div>

                @if(!isset($registrations) || $registrations->isEmpty())
                    <div class="px-6 py-10 text-sm text-gray-500">
                        You haven’t booked any event yet.
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($registrations as $reg)
                            @php($event = $reg->event)
                            <div class="px-6 py-5 flex items-center justify-between gap-6">
                                <div class="min-w-0">
                                    <div class="font-semibold text-gray-900 truncate">
                                        {{ $event->title ?? 'Untitled event' }}
                                    </div>

                                    <div class="text-sm text-gray-500 mt-1">
                                        {{ optional($event->start_date)->format('M d, Y, g:ia') ?? 'No date' }}
                                        @if(!empty($event->place))
                                            • {{ $event->place }}
                                        @endif
                                        @if($event->category)
                                            • <span class="text-indigo-600">{{ $event->category->name }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 shrink-0">
                                    <a href="{{ route('events.show', $event) }}"
                                       class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                                        View details
                                    </a>

                                    {{-- Cancel booking (only for normal users, not admin) --}}
                                    @if(auth()->check() && auth()->user()->role !== 'admin')
                                        <form method="POST" action="{{ route('events.unbook', $event) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-semibold hover:bg-gray-200 transition">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

    </main>

</body>
</html>
