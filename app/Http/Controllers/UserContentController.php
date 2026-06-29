<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class UserContentController extends Controller
{

    public function load($type)
    {
        $iduser = session('iduser');

        if (!$iduser) {
            return response('Unauthorized', 401);
        }

        switch ($type) {

            /*
            =========================
            QR CODE
            =========================
            */

            case 'qr':

                $user = DB::table('super_user')
                    ->where('iduser', $iduser)
                    ->first();

                if (!$user) {
                    return "<p>QR Code tidak ditemukan.</p>";
                }

                $qrData = json_encode([
                    'type' => 'user',
                    'iduser' => $iduser
                ]);

                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=400x400&ecc=H&margin=20&data="
                    . urlencode($qrData);

                return "
    <center>
        <img src='{$qrUrl}' style='width:320px;margin-bottom:10px;background:#fff;padding:12px;border-radius:12px;'>
        <p>
            <b>" . e($user->nama) . "</b><br>
            <small>ID: " . e($user->iduser) . "</small>
        </p>
    </center>
";

            /*
            =========================
            TAB SCAN BOOTH
            =========================
            */

            case 'scan':

                $data = DB::table('booth_kunjungan')

                    ->where('iduser', $iduser)

                    ->select(
                        'idbooth',
                        'nama_booth',
                        DB::raw('MAX(waktu_kunjungan) as last_visit')
                    )

                    ->groupBy(
                        'idbooth',
                        'nama_booth'
                    )

                    ->orderByDesc('last_visit')

                    ->get();

                $jumlah = $data->count();

                $html = "

        <div class='card'>

            <h3>

                <i class='fas fa-qrcode'></i>

                Scan Booth

            </h3>

            <p style='line-height:40px'>

                Lakukan scan QR di setiap booth
                untuk mendapatkan hadiah menarik!

            </p>

            <a href='/scanner'
               class='cta-button'>

                Scan Sekarang

            </a>

            <hr style='margin:15px 0;opacity:0.2;'>

            <p style='font-size:18px;'>

                <b>Jumlah Booth Dikunjungi:</b>

                <span style='color:#00b2ff;font-weight:bold;'>

                    {$jumlah}

                </span>

            </p>
    ";

                if ($jumlah > 0) {

                    $html .= "

            <ul style='margin-top:10px;
                       list-style-type:disc;
                       padding-left:20px;'>

        ";

                    foreach ($data as $r) {

                        $nama = e($r->nama_booth);

                        $html .= "

                <li>

                    {$nama}

                    <span style='color:#00ff8f;
                                 font-weight:bold;'>

                        +1

                    </span>

                </li>

            ";
                    }

                    $html .= "</ul>";

                } else {

                    $html .= "

            <p style='color:#ccc;'>

                Kamu belum mengunjungi booth manapun.

            </p>

        ";
                }

                $html .= "</div>";

                return $html;

            /*
            =========================
            TAB KEGIATAN SAYA
            =========================
            */

            case 'kegiatan':

                $kegiatan = DB::table('kegiatan_peserta')

                    ->where('iduser', $iduser)

                    ->orderBy('id_kegiatan', 'asc')

                    ->get();

                $html = "

        <div class='card'>

            <h3>

                <i class='fas fa-calendar-check'></i>

                Kegiatan Saya

            </h3>

    ";

                if ($kegiatan->count() > 0) {

                    $html .= "

            <ul style='margin-top:15px;
                       line-height:30px;
                       padding-left:20px;'>

        ";

                    foreach ($kegiatan as $k) {

                        $html .= "

                <li style='margin-bottom:15px;'>

                    <b>

                        " . e($k->nama_kegiatan) . "

                    </b>

                    <br>

                    <small style='color:#666;'>

                        " . e($k->waktu_kegiatan) . "

                    </small>

                </li>

            ";
                    }

                    $html .= "</ul>";

                } else {

                    $html .= "

            <p>

                Belum ada kegiatan yang kamu ikuti.

            </p>

        ";
                }

                $html .= "</div>";

                return $html;

            /*
            =========================
            PRESENSI
            =========================
            */

            case 'presensi':

                $presensi = DB::table('presensi_peserta as p')

                    ->leftJoin(
                        'kegiatan_peserta as k',
                        'p.id_kegiatan',
                        '=',
                        'k.id_kegiatan'
                    )

                    ->where('p.iduser', $iduser)

                    ->orderBy('p.waktu_presensi', 'desc')

                    ->select(
                        'p.*',
                        'k.nama_kegiatan'
                    )

                    ->get();

                $html = "
                    <div class='card'>

                        <h3>
                            <i class='fas fa-user-check'></i>
                            Presensi Saya
                        </h3>
                ";

                if ($presensi->count() > 0) {

                    $html .= "<ul class='presensi-list'>";

                    foreach ($presensi as $p) {

                        $statusBadge =
                            $p->status === 'Hadir'

                            ? "<span class='badge'
                                style='background:#4CAF50;
                                       color:#fff;
                                       padding:4px 8px;
                                       border-radius:6px;'>
                                Hadir
                               </span>"

                            : "<span class='badge'
                                style='background:#f44336;
                                       color:#fff;
                                       padding:4px 8px;
                                       border-radius:6px;'>
                                Belum Hadir
                               </span>";

                        $html .= "
                            <li style='margin-bottom:15px;'>

                                <b>
                                    " . e($p->nama_kegiatan ?? 'Tidak diketahui') . "
                                </b>

                                <br>

                                <small>
                                    Waktu:
                                    " . e($p->waktu_presensi ?? '-') . "
                                </small>

                                <br>

                                Status:
                                {$statusBadge}

                            </li>
                        ";
                    }

                    $html .= "</ul>";

                } else {

                    $html .= "
                        <p>
                            Belum ada data presensi.
                        </p>
                    ";
                }

                $html .= "</div>";

                return $html;

            /*
=========================
POINT & REWARD
=========================
*/

            case 'reward':

                /*
                =========================
                CONFIG REWARD
                =========================
                */

                $config = DB::table('reward_config')->first();

                $facultyTarget = $config->faculty_target ?? 7;
                $otherTarget = $config->other_target ?? 2;

                /*
                =========================
                HITUNG BOOTH FAKULTAS
                =========================
                */

                $facultyVisit = DB::table('booth_kunjungan as k')

                    ->leftJoin(
                        'booth as b',
                        'k.idbooth',
                        '=',
                        'b.idbooth'
                    )

                    ->where('k.iduser', $iduser)

                    ->where('b.kategori', 'Booth Fakultas')

                    ->distinct('b.idbooth')

                    ->count('b.idbooth');

                /*
                =========================
                HITUNG BOOTH LAINNYA
                =========================
                */

                $otherVisit = DB::table('booth_kunjungan as k')

                    ->leftJoin(
                        'booth as b',
                        'k.idbooth',
                        '=',
                        'b.idbooth'
                    )

                    ->where('k.iduser', $iduser)

                    ->where(function ($q) {

                        $q->whereNull('b.kategori')
                            ->orWhere('b.kategori', '!=', 'Booth Fakultas');

                    })

                    ->distinct('b.idbooth')

                    ->count('b.idbooth');

                /*
                =========================
                CEK ELIGIBLE
                =========================
                */

                $isEligible =
                    ($facultyTarget == 0 || $facultyVisit >= $facultyTarget)
                    &&
                    ($otherTarget == 0 || $otherVisit >= $otherTarget);

                /*
                =========================
                CEK SUDAH CLAIM
                =========================
                */

                $isClaimed = DB::table('reward_claim')
                    ->where('iduser', $iduser)
                    ->exists();

                /*
                =========================
                DESKRIPSI
                =========================
                */

                $descText =
                    "Kunjungi {$facultyTarget} booth fakultas dan {$otherTarget} booth pilihan untuk mendapatkan hadiah eksklusif.";

                /*
                =========================
                PERSENTASE
                =========================
                */

                $facultyPercent = $facultyTarget > 0
                    ? min(($facultyVisit / $facultyTarget) * 100, 100)
                    : 100;

                $otherPercent = $otherTarget > 0
                    ? min(($otherVisit / $otherTarget) * 100, 100)
                    : 100;

                /*
                =========================
                HTML
                =========================
                */

                $html = "

    <div class='card reward-card'>

        <h3>
            <i class='fas fa-gift'></i>
            Point & Reward
        </h3>

        <p class='subtitle'>

            {$descText}

            <br>

            <span class='notice'>

                <i class='fas fa-exclamation-circle'></i>

                Hadiah terbatas,
                cepat klaim sebelum kehabisan.

            </span>

        </p>

        <hr style='margin:15px 0;opacity:0.2;'>

        <!-- Booth Fakultas -->
        <div class='progress-section'>

            <div class='progress-label'>

                <i class='fas fa-university'></i>
                Booth Fakultas

            </div>

            <div class='progress-bar'>

                <div class='progress-fill'
                    style='width:{$facultyPercent}%'>
                </div>

            </div>

            <div class='progress-text'>

                {$facultyVisit} / {$facultyTarget}

            </div>

        </div>

        <!-- Booth Lain -->
        <div class='progress-section'>

            <div class='progress-label'>

                <i class='fas fa-store'></i>
                Booth Lainnya

            </div>

            <div class='progress-bar'>

                <div class='progress-fill'
                    style='width:{$otherPercent}%'>
                </div>

            </div>

            <div class='progress-text'>

                {$otherVisit} / {$otherTarget}

            </div>

        </div>

        <hr style='margin:15px 0;opacity:0.2;'>

        <div style='text-align:center;margin-top:10px;'>
    ";

                /*
                =========================
                STATUS
                =========================
                */

                if ($isClaimed) {

                    $html .= "

            <div class='reward-status success'>

                <i class='fas fa-check-circle'></i>

                Kamu sudah klaim hadiah!

            </div>

            <button class='cta-button'
                disabled
                style='background:rgba(0,255,100,0.2);
                       cursor:not-allowed;'>

                <i class='fas fa-gift'></i>
                Sudah Klaim Hadiah

            </button>
        ";

                } elseif ($isEligible) {

                    $html .= "

    <div class='reward-status success'>

        <i class='fas fa-check-circle'></i>

        Selamat! Kamu bisa klaim hadiah.

    </div>

    <button class='cta-button claimBtn'
        onclick='showClaimQR()'>

        <i class='fas fa-qrcode'></i>

        QR Klaim Hadiah

    </button>

    <p style='color:#aaa;
              font-size:13px;
              margin-top:6px;'>

        Tunjukkan QR ini ke petugas
        untuk menukar hadiahmu.

    </p>
";

                } else {

                    $html .= "

            <div class='reward-status wait'>

                <i class='fas fa-hourglass-half'></i>

                Lengkapi kunjungan untuk membuka QR hadiah.

            </div>

            <button class='cta-button'
                disabled
                style='opacity:0.5;
                       cursor:not-allowed;'>

                <i class='fas fa-lock'></i>

                QR Belum Tersedia

            </button>
        ";
                }

                $html .= "

        </div>

    </div>

    <style>

        .reward-card {

            background: #fff;
            border-radius: 16px;
            padding: 25px;
            color: #fff;
            box-shadow: 0 0 20px rgba(255, 0, 0, 0.25);
            max-width: 450px;
            margin: 25px auto;

        }

        .reward-card h3 {

            color: #ff3333;
            font-size: 1.6rem;
            margin-bottom: 8px;
            text-align: center;

        }

        .reward-card .subtitle {

            text-align: center;
            color: #000;
            font-size: 0.95rem;
            line-height: 1.4;

        }

        .reward-card .notice {

            display: block;
            color: #e74646;
            font-weight: 600;
            margin-top: 5px;

        }

        .progress-section {

            margin-top: 15px;

        }

        .progress-label {

            font-weight: 600;
            color: #e74646;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 6px;

        }

        .progress-bar {

            height: 10px;
            background: #333;
            border-radius: 8px;
            overflow: hidden;

        }

        .progress-fill {

            height: 100%;
            background: linear-gradient(90deg, #ff3333, #ff6666);
            border-radius: 8px;
            transition: width 0.6s ease-in-out;

        }

        .progress-text {

            margin-top: 6px;
            font-weight: 600;
            color: #e74646;
            font-size: 0.95rem;
            text-align: right;

        }

        .reward-status {

            padding: 10px;
            border-radius: 10px;
            margin-top: 15px;
            font-weight: 600;
            text-align: center;

        }

        .reward-status.success {

            background: rgba(0,255,100,0.1);
            color: #51b036;

        }

        .reward-status.wait {

            background: rgba(255,255,255,0.08);
            color: #ccc;

        }

    </style>
    ";

                return $html;

            /*
            =========================
            DEFAULT
            =========================
            */

            default:

                return "
                    <p>
                        Konten tidak ditemukan.
                    </p>
                ";
        }
    }
    public function generateClaimQR()
    {
        $iduser = session('iduser');

        if (!$iduser) {
            return "Session user tidak ditemukan.";
        }

        $user = DB::table('super_user')
            ->where('iduser', $iduser)
            ->first();

        if (!$user) {
            return "User tidak ditemukan.";
        }

        $qrData = json_encode([
            'type' => 'claim',
            'iduser' => $iduser
        ]);

        $qrUrl =
            "https://api.qrserver.com/v1/create-qr-code/?size=350x350&ecc=H&margin=20&data="
            . urlencode($qrData);

        return "
    <div class='card reward-claim-card'>

        <div class='qr-box'>
            <img src='{$qrUrl}' alt='QR Klaim Hadiah'>
        </div>

        <div class='user-info'>
            <h3>{$user->nama}</h3>
            <p>ID : {$user->iduser}</p>
        </div>

        <div class='status ok'>
            <i class='fas fa-gift'></i>
            Tunjukkan QR ini kepada petugas
            untuk melakukan klaim hadiah.
        </div>

    </div>
    ";
    }
}