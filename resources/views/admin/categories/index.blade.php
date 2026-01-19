<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Categories</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">

<header class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold">
            Event <span class="text-indigo-600">Planner</span>
        </a>

        <div class="flex items-center gap-6 text-sm">
            <a href="{{ route('admin.categories.index') }}" class="text-indigo-600 font-semibold underline underline-offset-4">Categories</a>
            <a href="{{ route('admin.events.index') }}" class="text-gray-700 hover:text-indigo-600">
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
</header>

<main class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-2xl font-bold text-indigo-700 mb-6">List of categories</h1>


    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif
@if(session('error'))
    <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
        {{ session('error') }}
    </div>
@endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4">
            <div class="font-semibold text-gray-700">Categories</div>

           <button onclick="document.getElementById('categoryModal').classList.remove('hidden')"
        class="px-4 py-2 rounded-md bg-indigo-600 text-white text-sm hover:bg-indigo-700">
    Create category
</button>

        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="text-left font-medium px-6 py-3">Category</th>
                        <th class="text-right font-medium px-6 py-3">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-800">
                                {{ $category->name }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <details class="relative inline-block">
                                    <summary class="cursor-pointer select-none px-2 py-1 rounded hover:bg-gray-100">
                                        ⋮
                                    </summary>
                                    <div class="absolute right-0 mt-2 w-32 bg-white border rounded-md shadow-lg z-10">
                                      <button type="button"
                                                class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-50"
                                                onclick="openEditCategoryModal({{ $category->id }}, @js($category->name))">
                                            Edit
                                        </button>

                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                            onsubmit="return confirm('Delete this category?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-50">
                                                Delete
                                            </button>
                                        </form>

                                    </div>
                                </details>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-6 py-10 text-center text-gray-500">
                                No categories found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4">
            {{ $categories->links() }}
        </div>
    </div>
</main>

</body>
<div id="categoryModal" class="fixed inset-0 bg-black/40 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl w-full max-w-md p-6 shadow-lg">
        <h2 class="text-xl font-semibold text-center mb-4">Create category</h2>

        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf

            <label class="block text-sm text-gray-600 mb-1">Category name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full border rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500"
                   placeholder="Enter category name">

            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror

            <div class="flex justify-end gap-3 mt-6">
                <button type="button"
                        onclick="document.getElementById('categoryModal').classList.add('hidden')"
                        class="px-4 py-2 rounded-md border text-indigo-600 hover:bg-gray-100">
                    Cancel
                </button>

                <button type="submit"
                        class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<div id="editCategoryModal" class="fixed inset-0 bg-black/40 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl w-full max-w-md p-6 shadow-lg">
        <h2 class="text-xl font-semibold text-center mb-4">Edit category</h2>

        <form id="editCategoryForm" method="POST" action="">
            @csrf
            @method('PUT')

            <label class="block text-sm text-gray-600 mb-1">Category name</label>
            <input id="editCategoryName" type="text" name="name"
                   class="w-full border rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">

            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror

            <div class="flex justify-end gap-3 mt-6">
                <button type="button"
                        onclick="closeEditCategoryModal()"
                        class="px-4 py-2 rounded-md border text-indigo-600 hover:bg-gray-100">
                    Cancel
                </button>

                <button type="submit"
                        class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditCategoryModal(id, name) {
        const modal = document.getElementById('editCategoryModal');
        const input = document.getElementById('editCategoryName');
        const form  = document.getElementById('editCategoryForm');

        input.value = name;
        form.action = `/admin/categories/${id}`; // matches your PUT route

        modal.classList.remove('hidden');
    }

    function closeEditCategoryModal() {
        document.getElementById('editCategoryModal').classList.add('hidden');
    }
</script>


</html>


