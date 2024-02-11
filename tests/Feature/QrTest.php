<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use App\Models\Jacket;

class QrTest extends TestCase
{
use RefreshDatabase;

    public function testCheckExistingJacket()
    {
    $jacket = Jacket::factory()->create([
    'modelno' => 'ABC123',
    ]);

    $response = $this->get(route('check', ['modelno' => $jacket->modelno]));

    $response->assertStatus(200)
    ->assertJson([
    'exists' => true,
    'jacket' => [
    'modelno' => $jacket->modelno,
        ],
    ]);
}

    public function testCheckNonExistingJacket()
    {
    $response = $this->get(route('check', ['modelno' => 'NONEXISTENT']));

    $response->assertStatus(200)
    ->assertJson([
    'exists' => false,
        ]);
    }

    public function testShareQRCode()
    {
        $response = $this->get(route('share.qrcode'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'message',
                'qr_code',
            ]);

        $decodedQrCode = base64_decode($response['qr_code']);

        $this->assertNotEmpty($decodedQrCode);

        $this->assertStringStartsWith('data:image/png;base64,', $response['qr_code']);

        $response->assertJson([
            'success' => true,
            'message' => 'QR code generated successfully',
        ]);
    }
}
