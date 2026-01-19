<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('start_date', 'desc')->paginate(8);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => ['required','string','max:255'],
            'category_id' => ['required','exists:categories,id'],
            'start_date'  => ['required','date'],
            'end_date'    => ['required','date','after_or_equal:start_date'],
            'place'       => ['required','string','max:255'],
            'capacity'    => ['required','integer','min:1'],

            'pricing'     => ['required','in:free,paid'],
            'price'       => ['nullable','numeric','min:0'],

            'image'       => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'description' => ['nullable','string'],
        ]);

        $isFree = $validated['pricing'] === 'free';

        // if paid: price is required
        if (!$isFree && ($request->price === null || $request->price === '')) {
            return back()
                ->withErrors(['price' => 'Amount is required when pricing is paid.'])
                ->withInput();
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
        }

        Event::create([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'start_date'  => $validated['start_date'],
            'end_date'    => $validated['end_date'],
            'place'       => $validated['place'],
            'capacity'    => $validated['capacity'],
            'is_free'     => $isFree ? 1 : 0,
            'price'       => $isFree ? null : $validated['price'],
            'image'       => $imagePath,
            'status'      => 'active',
            'category_id' => $validated['category_id'],
            'created_by'  => auth()->id(),
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
{
    $categories = Category::orderBy('name')->get();
    return view('admin.events.edit', compact('event', 'categories'));
}

public function update(Request $request, Event $event)
{
    $validated = $request->validate([
        'title' => ['required','string','max:255'],
        'category_id' => ['required','exists:categories,id'],
        'start_date' => ['required','date'],
        'end_date' => ['required','date','after_or_equal:start_date'],
        'place' => ['required','string','max:255'],
        'capacity' => ['required','integer','min:1'],
        'is_free' => ['required','boolean'],
        'price' => ['nullable','numeric','min:0'],
        'description' => ['nullable','string'],
        'image' => ['nullable','image','max:2048'],
    ]);

    // if free => price must be null/0
    if ((int)$validated['is_free'] === 1) {
        $validated['price'] = null;
    } else {
        if ($validated['price'] === null) {
            return back()->withErrors(['price' => 'Price is required if event is paid.'])->withInput();
        }
    }

    // image upload (optional)
    if ($request->hasFile('image')) {
        // delete old image if exists
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        $validated['image'] = $request->file('image')->store('events', 'public');
    }

    $event->update($validated);

    return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
}

public function archive(Event $event)
{
    $event->update(['status' => 'archived']);

    return redirect()->route('admin.events.index')->with('success', 'Event archived successfully.');
}
}
