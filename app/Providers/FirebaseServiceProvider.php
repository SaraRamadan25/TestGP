<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton('firebase', function ($app) {
            $serviceAccount = config('firebase.sdk.service_account.file');
            $databaseUri = config('firebase.sdk.database_url');
            return (new Factory)
                ->withServiceAccount($serviceAccount)
                ->withDatabaseUri($databaseUri);
        });
    }
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
