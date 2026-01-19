<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">

<header class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold">
            Event <span class="text-indigo-600">Planner</span>
        </a>

        <div class="flex items-center gap-6 text-sm">
        

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

<main class="max-w-5xl mx-auto px-6 py-10">
    <h1 class="text-2xl font-bold text-center mb-8">Create Event</h1>

    <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <div class="space-y-4">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Event Title</label>
                <input name="title" value="{{ old('title') }}"
                       class="w-full h-11 rounded-md border border-gray-200 px-3 bg-white focus:ring-indigo-500 focus:border-indigo-500">
                @error('title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Category</label>
                <select name="category_id"
                        class="w-full h-11 rounded-md border border-gray-200 px-3 bg-white focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Select category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Start date</label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date') }}"
                           class="w-full h-11 rounded-md border border-gray-200 px-3 bg-white focus:ring-indigo-500 focus:border-indigo-500">
                    @error('start_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">End date</label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date') }}"
                           class="w-full h-11 rounded-md border border-gray-200 px-3 bg-white focus:ring-indigo-500 focus:border-indigo-500">
                    @error('end_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Place</label>
                    <input name="place" value="{{ old('place') }}"
                           class="w-full h-11 rounded-md border border-gray-200 px-3 bg-white focus:ring-indigo-500 focus:border-indigo-500">
                    @error('place') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Capacity</label>
                    <input type="number" name="capacity" value="{{ old('capacity') }}"
                           class="w-full h-11 rounded-md border border-gray-200 px-3 bg-white focus:ring-indigo-500 focus:border-indigo-500">
                    @error('capacity') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Pricing</label>
                    <select id="pricing" name="pricing"
                            class="w-full h-11 rounded-md border border-gray-200 px-3 bg-white focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="free" @selected(old('pricing','free')==='free')>Free Access</option>
                        <option value="paid" @selected(old('pricing')==='paid')>Paid</option>
                    </select>
                    @error('pricing') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Amount</label>
                    <input id="price" type="number" step="0.01" name="price" value="{{ old('price') }}"
                           class="w-full h-11 rounded-md border border-gray-200 px-3 bg-white focus:ring-indigo-500 focus:border-indigo-500">
                    @error('price') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-xl font-bold text-center mb-4">Event Description</h2>

            <div>
                <label class="block text-xs text-gray-500 mb-2">Event Image</label>
                <div class="w-full h-48 rounded-xl border border-gray-200 bg-gray-200 flex items-center justify-center overflow-hidden">
                    <span class="text-gray-500 text-sm">Upload image</span>
                </div>
                <input type="file" name="image" class="mt-3 block w-full text-sm">
                @error('image') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mt-5">
                <label class="block text-xs text-gray-500 mb-2">Event Description</label>
                <textarea name="description" rows="5"
                          class="w-full rounded-md border border-gray-200 px-3 py-2 bg-white focus:ring-indigo-500 focus:border-indigo-500"
                          placeholder="Type here...">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <button type="submit"
                class="w-full h-11 rounded-md bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
            Create event
        </button>
    </form>
</main>

<script>
    // disable amount input when free
    function updatePriceState() {
        const pricing = document.getElementById('pricing');
        const price = document.getElementById('price');
        const isFree = pricing.value === 'free';
        price.disabled = isFree;
        if (isFree) price.value = '';
    }

    document.getElementById('pricing').addEventListener('change', updatePriceState);
    updatePriceState();
</script>

</body>
</html>
