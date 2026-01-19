<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registration;


class AdminRegistrationController extends Controller
{
    public function index()
    {
        // newest first, load event + user to display them in the table
        $registrations = Registration::with(['event', 'user'])
            ->latest()
            ->paginate(10);

        return view('admin.registrations.index', compact('registrations'));
    }
}
