<?php
/*
File: resources/views/admin/events/edit.blade.php

Requirements:
- Tailwind (Breeze) is installed.
- Routes exist:
    admin.events.update (PUT /admin/events/{event})
    admin.events.index
- Controller passes: $event, $categories
- Images stored on disk 'public' (php artisan storage:link)
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Event</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-900">

{{-- TOP NAV (same style as your admin pages) --}}
<nav class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="text-xl font-bold">
            <span>Event</span><span class="text-indigo-600">Planner</span>
        </div>

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
</nav>

<main class="max-w-3xl mx-auto px-6 py-10">

    {{-- Page header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Event</h1>
        <p class="text-sm text-gray-500 mt-1">Update the event information, pricing, and image.</p>
    </div>

    {{-- Global errors --}}
    @if ($errors->any())
        <div class="mb-6 rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
            <div class="font-semibold mb-1">Please fix the errors below.</div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Edit form --}}
    <form method="POST"
          action="{{ route('admin.events.update', $event) }}"
          enctype="multipart/form-data"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        @csrf
        @method('PUT')

        {{-- Title --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">Event Title</label>
            <input type="text" name="title" value="{{ old('title', $event->title) }}"
                   class="w-full rounded-md border-gray-200 focus:border-indigo-500 focus:ring-indigo-500"
                   placeholder="Title">
            @error('title')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Category --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select name="category_id"
                    class="w-full rounded-md border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Select category</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(old('category_id', $event->category_id) == $cat->id)>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Dates (2 columns) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start date</label>
                <input type="date" name="start_date"
                       value="{{ old('start_date', \Carbon\Carbon::parse($event->start_date)->format('Y-m-d')) }}"
                       class="w-full rounded-md border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                @error('start_date')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End date</label>
                <input type="date" name="end_date"
                       value="{{ old('end_date', \Carbon\Carbon::parse($event->end_date)->format('Y-m-d')) }}"
                       class="w-full rounded-md border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                @error('end_date')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Place & Capacity (2 columns) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Place</label>
                <input type="text" name="place" value="{{ old('place', $event->place) }}"
                       class="w-full rounded-md border-gray-200 focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Place">
                @error('place')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Capacity</label>
                <input type="number" name="capacity" min="1" value="{{ old('capacity', $event->capacity) }}"
                       class="w-full rounded-md border-gray-200 focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Capacity">
                @error('capacity')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Pricing (2 columns) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pricing</label>
                <select id="is_free" name="is_free"
                        class="w-full rounded-md border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="1" @selected((int)old('is_free', $event->is_free) === 1)>Free Access</option>
                    <option value="0" @selected((int)old('is_free', $event->is_free) === 0)>Paid</option>
                </select>
                @error('is_free')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                <input id="price" type="number" step="0.01" name="price"
                       value="{{ old('price', $event->price) }}"
                       class="w-full rounded-md border-gray-200 focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Amount">
                @error('price')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Description section --}}
        <h2 class="text-xl font-bold text-gray-900 mb-4 text-center">Event Description</h2>

        {{-- Image preview + upload --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-2">Event Image</label>

            <div class="w-full rounded-xl bg-gray-100 border border-gray-200 overflow-hidden">
                @php
                    // If you store image as "events/xxx.jpg" in public disk, use Storage::url in controller or directly:
                    $imageUrl = $event->image ? $event->image_url : null;
                @endphp

                <div class="h-48 flex items-center justify-center">
                    @if($imageUrl)
                        <img id="preview" src="{{ $imageUrl }}" alt="Event image"
                             class="h-full w-full object-cover">
                    @else
                        <img id="preview" src="" alt=""
                             class="hidden h-full w-full object-cover">
                        <div id="placeholder" class="text-gray-400 text-sm">No image</div>
                    @endif
                </div>
            </div>

            <input type="file" name="image" accept="image/*"
                   class="mt-3 block w-full text-sm text-gray-600
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-md file:border-0
                          file:text-sm file:font-semibold
                          file:bg-indigo-50 file:text-indigo-700
                          hover:file:bg-indigo-100"
                   onchange="previewImage(event)">
            @error('image')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Textarea --}}
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Event Description</label>
            <textarea name="description" rows="5"
                      class="w-full rounded-md border-gray-200 focus:border-indigo-500 focus:ring-indigo-500"
                      placeholder="Type here...">{{ old('description', $event->description) }}</textarea>
            @error('description')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('admin.events.index') }}"
               class="px-4 py-2 rounded-md border border-gray-200 text-gray-700 hover:bg-gray-50">
                Cancel
            </a>

            <button type="submit"
                    class="px-5 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                Save changes
            </button>
        </div>
    </form>
</main>

<script>
    // Disable price input if "Free Access"
    function syncPriceDisabled() {
        const isFree = document.getElementById('is_free').value;
        const price = document.getElementById('price');
        if (isFree === '1') {
            price.value = '';
            price.setAttribute('disabled', 'disabled');
            price.classList.add('bg-gray-100');
        } else {
            price.removeAttribute('disabled');
            price.classList.remove('bg-gray-100');
        }
    }

    document.getElementById('is_free').addEventListener('change', syncPriceDisabled);
    syncPriceDisabled();

    function previewImage(event) {
        const file = event.target.files && event.target.files[0];
        if (!file) return;

        const preview = document.getElementById('preview');
        const placeholder = document.getElementById('placeholder');

        const url = URL.createObjectURL(file);
        preview.src = url;
        preview.classList.remove('hidden');
        if (placeholder) placeholder.classList.add('hidden');
    }
</script>

</body>
</html>

