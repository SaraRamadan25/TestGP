<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LocationApiTest extends TestCase
{
    /**
     * Test the positionstack-api endpoint with a POST request.
     *
     * @return void
     */
    public function testPositionStackApi()
    {
        $response = $this->postJson('api/positionstack-api', [
            'long'=> '77.5946',
            'lat'=> '12.9716',
        ]);

        $response->assertStatus(200);
    }

}
