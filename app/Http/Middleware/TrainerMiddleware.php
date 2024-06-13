<?php

namespace App\Http\Middleware;

use App\Models\Trainer;
use Closure;
use GPBMetadata\Google\Api\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrainerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $trainer = $request->route('trainer');

        if ($trainer && Auth::check() && Auth::user()->username === $trainer->username) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized access'], 403);

    }
}
