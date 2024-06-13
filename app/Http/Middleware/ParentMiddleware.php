<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ParentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Check if the user is a parent
        if ($user && $user->role_id === 1) {
            // Check if the authenticated parent is the one making the request
            if ($request->route('user')->id === $user->id) {
                return $next($request);
            }
        }

        return response()->json(['message' => 'Forbidden'], 403);
    }

}
