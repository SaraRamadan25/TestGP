<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherApiTest extends TestCase
{
    /**
     * Test the OpenWeatherMap API endpoint.
     *
     * @return void
     */
    public function testOpenWeatherMapApi()
    {
        $lat = 44.34;
        $lon = 10.99;
        $appid = 'ba8c18405b17fffbf1ff4f44f69003ad';
        $units='metric';
        $url = "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&appid=$appid&units=$units";

        $response = Http::get($url);

        $this->assertTrue($response->getStatusCode() >= 200 && $response->getStatusCode() < 300);

        $this->assertIsArray($response->json());

        $this->assertArrayHasKey('main', $response->json());
    }
}
