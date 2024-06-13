<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use App\Models\Trainer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrainerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user instanceof Trainer) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->attributes->set('trainer', $user);

        return $next($request);
    }
}
