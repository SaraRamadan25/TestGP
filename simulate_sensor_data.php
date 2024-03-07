<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;

$client = new Client();

try {
    $response = $client->request('GET', 'http://127.0.0.1:8000/api/simulateSensorData');

    echo $response->getBody();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
