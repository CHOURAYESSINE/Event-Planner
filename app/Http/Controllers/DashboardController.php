<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query()->where('status', 'active');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('place', 'like', "%{$q}%");
            });
        }

        $query->where('start_date', '>=', now())
              ->orderBy('start_date', 'asc');

        $events = $query->paginate(6)->withQueryString();

        return view('dashboard', compact('events'));
    }
}
