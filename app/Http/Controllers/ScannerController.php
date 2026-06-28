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
    $user = DB::table('super_user')
        ->where('iduser', $iduser)
        ->first();

    if (!$user) {
        return "
            <div class='error'>
                Peserta tidak ditemukan.
            </div>
        ";
    }

    $claimed = DB::table('reward_claim')
        ->where('iduser', $iduser)
        ->exists();

    $button = $claimed
        ? "<button class='btn-hadir active' disabled>Sudah Klaim</button>"
        : "<button
                class='btn-hadir'
                onclick='confirmReward({$iduser})'>
                Konfirmasi Klaim
           </button>";

    return "

    <div class='participant-info'>

        <h3>
            <i class='fas fa-gift'></i>
            Klaim Hadiah
        </h3>

        <div class='info-grid'>

            <div>
                <b>Nama</b><br>
                {$user->nama}
            </div>

            <div>
                <b>Email</b><br>
                {$user->email}
            </div>

            <div>
                <b>No HP</b><br>
                {$user->hp}
            </div>

            <div>
                <b>Status</b><br>" .

        ($claimed
            ? "<span class='status hadir'>Sudah Klaim</span>"
            : "<span class='status belum-hadir'>Belum Klaim</span>")

        . "

            </div>

        </div>

        <br>

        {$button}

    </div>

    ";
}

public function confirmReward(Request $request)
{
    $iduser = $request->iduser;

    if (
        DB::table('reward_claim')
            ->where('iduser', $iduser)
            ->exists()
    ) {

        return response()->json([
            'success' => false,
            'message' => 'Hadiah sudah pernah diklaim.'
        ]);
    }

    DB::table('reward_claim')->insert([

        'iduser' => $iduser,

        'claimed_at' => now(),

        'claimed_by' => session('admin_id')

    ]);

    return response()->json([

        'success' => true,

        'message' => 'Hadiah berhasil diklaim.'

    ]);
}
}