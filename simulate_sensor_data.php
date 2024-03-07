<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;

$client = new Client();

$mockData = [
    'gpsData' => json_encode([
        'lat' => 51.509865,
        'lng' => -0.118092
    ]),
    'healthData' => json_encode([
        'heartRate' => 72,
        'spo2' => 98
    ]),
    'relayStatus' => true
];

try {
    $response = $client->request('POST', 'http://127.0.0.1:8000/api/getData', [
        'json' => $mockData
    ]);

    echo $response->getBody();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
