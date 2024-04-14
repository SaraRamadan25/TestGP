<?php

return [
    'sdk' => [
        'service_account' => [
            'file' => config_path('firebase/serviceAccount.json'),
        ],
        'database_url' => env('FIREBASE_DATABASE_URL'),
    ],
];
