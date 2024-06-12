<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class QrcodeController extends Controller
{
    public function shareQRCode(): JsonResponse
    {
        $randomContent = uniqid();

        try {
            $qrCode = QrCode::format('png')->size(300)->generate("Random Content: $randomContent");

            $base64QRCode = 'data:image/png;base64,'.base64_encode($qrCode);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate QR code: '.$e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'QR code generated successfully',
            'qr_code' => $base64QRCode,
        ]);
    }
}
