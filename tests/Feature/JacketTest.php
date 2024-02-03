<?php

namespace Tests\Feature;

use App\Models\Jacket;
use App\Models\QrCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JacketTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testScanJacket(): void
    {
        // Create a User
        $user = User::factory()->create();

        // Create a Jacket associated with the User without saving to the database
        $jacket = Jacket::factory()->make([
            'user_id' => $user->id,
            'modelno' => '123458',
            'batteryLevel' => 100,
            'start_rent_time' => now(),
            'end_rent_time' => now()->addDays(7),
        ])->makeVisible(['id', 'created_at', 'updated_at']);

        // Convert the Jacket model to an array
        $jacketArray = $jacket->toArray();

        // Remove the 'id' key if it's present in $jacketArray
        unset($jacketArray['id']);

        // Create a QrCode for the Jacket in memory
        $qrCode = QrCode::factory()->forJacket($jacketArray)->make([
            'jacket_id' => $jacketArray['id'] ?? null, // Use 'id' if present, or null otherwise
        ]);

        // Assert that the Jacket and QR code exist in memory with the correct attributes
        $this->assertEquals($jacketArray, $jacket->toArray());
        $this->assertEquals(['jacket_id' => $jacketArray['id'] ?? null], $qrCode->toArray());

        // Assert that the Jacket exists in the database
        $this->assertDatabaseHas('jackets', $jacketArray);

        // Assert that the QR code exists in the database for the jacket
        $this->assertDatabaseHas('qr_codes', ['jacket_id' => $jacketArray['id']]);

        // Simulate a successful scan without persisting to the database
        $response = $this->post(route('scan.jacket'), [
            'model_no' => $jacketArray['modelno'],
        ]);

        // Add assertions for the response
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Scan successful',
            ]);

        // Ensure the models are not persisted to the database
        $this->assertDatabaseCount('jackets', 0);
        $this->assertDatabaseCount('qr_codes', 0);
    }
}
