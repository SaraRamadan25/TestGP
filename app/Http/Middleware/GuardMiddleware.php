<?php

namespace App\Http\Middleware;

use App\Models\Guard;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuardMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user instanceof Guard) {
            if ($request->route('guard')->id === $user->id) {
                return $next($request);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
