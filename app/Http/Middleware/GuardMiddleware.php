<?php

namespace App\Http\Middleware;

use App\Models\Guard;
use Closure;
use Illuminate\Http\Request;

class GuardMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user instanceof Guard) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->attributes->set('guard', $user);

        return $next($request);
    }
}
