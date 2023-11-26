<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrcodeController extends Controller
{

    public function generateQrCode($id)
    {
        $outputDirectory = public_path('qrcodes');

        // Create the directory if it doesn't exist
        if (!is_dir($outputDirectory)) {
            mkdir($outputDirectory);
        }

        // Generate and save the QR code with a unique identifier
        QrCode::format('png')->size(300)->generate("Hello, world! ID: $id", $outputDirectory."/qrcode_$id.png");

        // Return the QR code as a response
        return response(QrCode::size(300)->generate("Hello, world! ID: $id"))->header('Content-Type', 'image/png');
    }



}
