<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;

$client = new Client([
    'verify' => false, // Add this line
]);

try {
    $response = $client->request('GET', 'http://save-lives.online/api/simulateSensorData');

    echo $response->getBody();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
