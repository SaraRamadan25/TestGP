<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HealthInfoTest extends TestCase
{
    use RefreshDatabase;

    public function test_that_health_info_has_been_saved()
    {
        $user=User::factory()->create();
        $this->actingAs($user);
        $response = $this->post('/api/submit-health-data', [
            'name' => 'Sara',
            'age' => 22,
            'height' => 168,
            'weight' => 50,
            'heart_rate' => 90,
            'blood_type' => 'A+',
            'diseases' => 'none',
            'allergies' => 'none'
        ]);

        $this->assertDatabaseHas('health', [
            'name' => 'Sara',
            'age' => 22,
            'height' => 168,
            'weight' => 50,
            'heart_rate' => 90,
            'blood_type' => 'A+',
            'diseases' => 'none',
            'allergies' => 'none',
            'user_id' => $user->id
        ]);

    }
}
