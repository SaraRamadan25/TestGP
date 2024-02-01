<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('success', function ($data, $message = null, $code = 200) {
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => $message,
            ], $code);
        });
        Response::macro('error', function ($error,$message, $code=400) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'error' => $error
            ], $code);
        });

    }
}
