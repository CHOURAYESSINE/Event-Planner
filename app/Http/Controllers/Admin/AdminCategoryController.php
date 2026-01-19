<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->paginate(8);

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        // Create category
        Category::create([
            'name' => $request->name,
        ]);

        
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
    ]);

    $category->update(['name' => $request->name]);

    return redirect()->route('admin.categories.index')
        ->with('success', 'Category updated successfully.');
}

public function destroy(Category $category)
{
    if ($category->events()->exists()) {
        return redirect()->route('admin.categories.index')
            ->with('error', 'You cannot delete this category because it has events.');
    }

    $category->delete();

    return redirect()->route('admin.categories.index')
        ->with('success', 'Category deleted successfully.');
}
}