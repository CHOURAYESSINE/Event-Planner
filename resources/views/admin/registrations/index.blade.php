<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrations</title>

    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('partials.header')

    <main class="max-w-7xl mx-auto px-6 py-10">
        <h1 class="text-2xl font-bold text-indigo-600 mb-6">List of registrations</h1>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 font-semibold">Registrations</div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr class="text-left">
                            <th class="px-6 py-4 font-medium">Event title</th>
                            <th class="px-6 py-4 font-medium">Start date</th>
                            <th class="px-6 py-4 font-medium">User email</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($registrations as $reg)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $reg->event?->title ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-gray-700">
                                    {{ optional($reg->event?->start_date)->format('M j, Y, g:ia') ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-gray-700">
                                    {{ $reg->user?->email ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-gray-500">
                                    No registrations found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($registrations, 'links'))
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $registrations->links() }}
                </div>
            @endif
        </div>
    </main>
</body>
</html>
