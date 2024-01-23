<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrcodeController extends Controller
{
    public function generateQrCode($id)
    {
        $outputDirectory = public_path('qrcodes');

        // Create the directory if it doesn't exist, using correct path separator
        if (!is_dir($outputDirectory)) {
            mkdir($outputDirectory, 0755, true); // Ensure proper permissions
        }

        // Generate and save the QR code with error handling
        try {
            QrCode::format('png')->size(300)->generate("Hello, world! ID: $id",
                $outputDirectory.DIRECTORY_SEPARATOR."qrcode_$id.png");
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate QR code: '.$e->getMessage(),
            ], 500);
        }

        // Return the generated QR code as a response
        return response()->file($outputDirectory.DIRECTORY_SEPARATOR."qrcode_$id.png")->header('Content-Type',
            'image/png');
    }


    public function shareQRCode($receiver_user_id)
    {
        try {
            // Get the current user ID
            $sender_user_id = auth()->id();

            $qrCodeContent = "Action for user $receiver_user_id initiated by user $sender_user_id";

            // Generate the QR code in SVG format
            $qrCodeSvg = QrCode::format('svg')->size(300)->generate($qrCodeContent);

            return response()->json([
                'success' => true,
                'message' => 'QR Code shared successfully',
                'qr_code_content' => $qrCodeContent,
                'qr_code_svg' => $qrCodeSvg,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to share QR code: '.$e->getMessage(),
            ], 500);
        }
    }
}
