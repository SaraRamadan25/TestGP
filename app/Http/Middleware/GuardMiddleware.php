<?php

namespace App\Http\Middleware;

use App\Models\Guard;
use Closure;
use Illuminate\Http\Request;

class GuardMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Check if the user is a guard
        if ($user && $user instanceof Guard) {
            // Check if the authenticated guard is the one making the request
            if ($request->route('guard')->id === $user->id) {
                return $next($request);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
