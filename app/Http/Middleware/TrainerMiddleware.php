<?php

namespace App\Http\Middleware;

use App\Models\Trainer;
use Closure;
use GPBMetadata\Google\Api\Log;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrainerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $username = $request->route('username');

        $trainer = Trainer::where('username', $username)->first();
        if (!$trainer) {
            return response()->json(['message' => 'Trainer not found'], 404);
        }

        $request->attributes->set('trainer', $trainer);

        return $next($request);
    }
}
