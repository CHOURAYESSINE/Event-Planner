<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 2️⃣ Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Access denied: Admins only');
        }

        // 3️⃣ If admin → allow access
        return $next($request);
    }
}
