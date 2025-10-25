<?php

namespace App\Http\Controllers;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;

class QRController extends Controller
{
    public function schedule(Request $request)
    {
        $payload = [
            'type' => 'attendance',
            'schedule_id' => (int) $request->query('schedule_id', 0),
            'date' => $request->query('date') ?? date('Y-m-d'),
        ];
        $text = json_encode($payload);
        $options = new QROptions(['outputType' => QRCode::OUTPUT_IMAGE_PNG, 'scale' => 5]);
        $image = (new QRCode($options))->render($text);
        return response($image)->header('Content-Type', 'image/png');
    }
}