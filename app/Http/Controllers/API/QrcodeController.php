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
    public function scanqr($username): BinaryFileResponse|JsonResponse
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found with the provided username.',
            ], 404);
        }

        $outputDirectory = public_path('qrcodes');

        if (!is_dir($outputDirectory)) {
            mkdir($outputDirectory, 0755, true);
        }

        try {
            QrCode::format('png')->size(300)->generate("Hello, world! Username: $username",
                $outputDirectory.DIRECTORY_SEPARATOR."qrcode_$username.png");
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate QR code: '.$e->getMessage(),
            ], 500);
        }

        $file = $outputDirectory.DIRECTORY_SEPARATOR."qrcode_$username.png";

        return response()->download($file, "qrcode_$username.png", [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'inline; filename="qrcode_'.$username.'.png"',
        ]);
    }

    public function checkQrCodeExistence(Request $request): JsonResponse
    {
        //dd($request->all());
        $qrCodeData = $request->input('content');

        $qrCode = QrCode::where('content', $qrCodeData)->first();

        if ($qrCode) {
            $jacket = $qrCode->jacket;

            if ($jacket) {
                return response()->json([
                    'exists' => true,
                    'jacket' => [
                        'modelno' => $jacket->modelno,
                        'batteryLevel' => $jacket->batteryLevel,
                    ],
                ]);
            } else {
                return response()->json([
                    'exists' => false,
                ]);
            }
        } else {
            return response()->json([
                'exists' => false,
            ]);
        }
    }

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