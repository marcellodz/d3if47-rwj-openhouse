<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        /*
        =========================
        CEK LOGIN
        =========================
        */

        if (!session('loggedin')) {

            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu.'
            ], 401);

        }

        /*
        =========================
        AMBIL QR DATA
        =========================
        */

        $qrData = $request->qr_data;

        if (!$qrData) {

            return response()->json([
                'success' => false,
                'message' => 'QR tidak valid.'
            ]);

        }

        /*
        =========================
        DECODE JSON QR
        =========================
        */

        $decoded = json_decode($qrData, true);

        if (
            !$decoded
            ||
            !isset($decoded['idbooth'])
        ) {

            return response()->json([
                'success' => false,
                'message' => 'Format QR tidak dikenali.'
            ]);

        }

        /*
        =========================
        AMBIL DATA BOOTH
        =========================
        */

        $idbooth = $decoded['idbooth'];

        $booth = DB::table('booth')
            ->where('idbooth', $idbooth)
            ->first();

        if (!$booth) {

            return response()->json([
                'success' => false,
                'message' => 'Booth tidak ditemukan.'
            ]);

        }

        /*
        =========================
        DATA USER LOGIN
        =========================
        */

        $iduser = session('iduser');
        $nama = session('nama');

        /*
        =========================
        CEK SUDAH PERNAH SCAN?
        =========================
        */

        $exists = DB::table('booth_kunjungan')
            ->where('iduser', $iduser)
            ->where('idbooth', $idbooth)
            ->exists();

        if ($exists) {

            return response()->json([
                'success' => false,
                'message' => 'Booth sudah pernah discan.'
            ]);

        }

        /*
        =========================
        INSERT KUNJUNGAN
        =========================
        */

        DB::table('booth_kunjungan')
            ->insert([
                'iduser' => $iduser,
                'nama_peserta' => $nama,
                'idbooth' => $booth->idbooth,
                'nama_booth' => $booth->nama_booth,
                'kategori' => $booth->kategori,
                'waktu_kunjungan' => now(),
            ]);

        /*
        =========================
        TOTAL BOOTH
        =========================
        */

        $totalBooth = DB::table('booth_kunjungan')
            ->where('iduser', $iduser)
            ->distinct('idbooth')
            ->count('idbooth');

        /*
        =========================
        RESPONSE
        =========================
        */

        return response()->json([
            'success' => true,
            'message' => 'Booth berhasil discan.',
            'nama_booth' => $booth->nama_booth,
            'total_booth' => $totalBooth
        ]);
    }

    public function rewardContent($iduser)
{
    return "
        <div style='padding:25px;text-align:center'>
            <h2 style='color:#00ff8f'>
                Reward QR Berhasil Dibaca
            </h2>

            <p>ID User : <b>{$iduser}</b></p>
        </div>
    ";
}
}