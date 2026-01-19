<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Events</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">

<header class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold">
            Event <span class="text-indigo-600">Planner</span>
        </a>

        <div class="flex items-center gap-6 text-sm">
            <a href="{{ route('admin.categories.index') }}" class="text-gray-700 hover:text-indigo-600">Categories</a>
            <a href="{{ route('admin.events.index') }}" class="text-indigo-600 font-semibold underline underline-offset-4">
                Events
            </a>

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

<main class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-2xl font-bold text-indigo-700 mb-6">List of Events</h1>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4">
            <div class="font-semibold text-gray-700">Events</div>

            <a href="{{ route('admin.events.create') }}" class="px-4 py-2 rounded-md bg-indigo-600 text-white text-sm hover:bg-indigo-700 transition">
    Create event
</a>

        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="text-left font-medium px-6 py-3">Event name</th>
                        <th class="text-left font-medium px-6 py-3">Start date</th>
                        <th class="text-left font-medium px-6 py-3">End Date</th>
                        <th class="text-left font-medium px-6 py-3">Pricing</th>
                        <th class="text-left font-medium px-6 py-3">Capacity</th>
                        <th class="text-left font-medium px-6 py-3">Place</th>
                        <th class="text-right font-medium px-6 py-3">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($events as $event)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $event->title }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ \Carbon\Carbon::parse($event->start_date)->format('M j, Y, g:ia') }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ \Carbon\Carbon::parse($event->end_date)->format('M j, Y, g:ia') }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                @if((int)$event->is_free === 1)
                                    Free
                                @else
                                    {{ $event->price }}
                                @endif
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $event->capacity }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $event->place }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <details class="relative inline-block">
                                    <summary class="cursor-pointer select-none px-2 py-1 rounded hover:bg-gray-100">
                                        ⋮
                                    </summary>
                                    <div class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-md shadow-lg overflow-hidden z-50">

    <a href="{{ route('admin.events.edit', $event) }}"
       class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-50">
        Edit
    </a>

    <form method="POST" action="{{ route('admin.events.archive', $event) }}">
        @csrf
        @method('PATCH')
        <button type="submit"
                class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-50">
            Archive
        </button>
    </form>

</div>

                                </details>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                No events found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4">
            {{ $events->links() }}
        </div>
    </div>
</main>

</body>
</html>
