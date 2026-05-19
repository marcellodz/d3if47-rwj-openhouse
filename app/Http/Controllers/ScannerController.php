<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScannerController extends Controller
{
    public function index()
    {
        if (!session('loggedin')) {
            return redirect('/login');
        }

        return view('user.scanner');
    }

    public function processQr(Request $request)
    {
        $qrData = $request->qr_data;

        // sementara debug dulu
        return response()->json([
            'success' => true,
            'message' => 'QR berhasil diterima',
            'data' => $qrData
        ]);
    }
}