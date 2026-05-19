<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(Request $request)
    {

        /* ================================
           CLEAN DATA
        ================================ */

        $nama = trim($request->nama);
        $hp = preg_replace('/[^0-9]/', '', $request->hp);

        if (substr($hp, 0, 2) == "08") {
            $hp = "62" . substr($hp, 1);
        }

        if (substr($hp, 0, 2) != "62") {
            $hp = "62" . $hp;
        }

        $email = trim($request->email);

        $kode = trim($request->password);

        /* ================================
           ID KOTA
        ================================ */

        $idkota = DB::table('porsi_sma')
            ->where('kota', $request->kota)
            ->value('idkota') ?? 0;

        /* ================================
           SEKOLAH
        ================================ */

        $sekolah = trim($request->sekolah ?? '');
        $sekolah_lainnya = trim($request->sekolah_lainnya ?? '');

        if ($sekolah === "Lainnya") {

            $sekolah_input = "";
            $sekolah_lainnya_input = $sekolah_lainnya;

        } elseif ($sekolah !== "") {

            $sekolah_input = $sekolah;
            $sekolah_lainnya_input = "";

        } else {

            $sekolah_input = "";
            $sekolah_lainnya_input = $sekolah_lainnya;

        }

        /* ================================
          KEGIATAN MULTI
       ================================ */

        $seminar_titles = [];
        $seminar_times = [];

        $trial_titles = [];
        $trial_times = [];

        if ($request->filled('kegiatan')) {

            $items = $request->kegiatan;

            if (!is_array($items)) {
                $items = [$items];
            }

            foreach ($items as $item) {

                if (!is_string($item) || trim($item) === "") {
                    continue;
                }

                $parts = explode("|", $item, 2);

                $waktu = trim($parts[0] ?? "-");
                $judul = trim($parts[1] ?? "");

                // fallback kalau frontend cuma kirim judul
                if ($judul === "" && !empty($parts[0])) {

                    $judul = trim($parts[0]);
                    $waktu = "-";

                }

                if ($judul === "") {
                    continue;
                }

                if (stripos($judul, "Trial Class") !== false) {

                    $trial_titles[] = $judul;
                    $trial_times[] = $waktu;

                } else {

                    $seminar_titles[] = $judul;
                    $seminar_times[] = $waktu;

                }

            }
        }

        $seminar = !empty($seminar_titles)
            ? implode("|", $seminar_titles)
            : "Tidak Mengikuti";

        $seminar_waktu = !empty($seminar_times)
            ? implode("|", $seminar_times)
            : "-";

        $trial_class = !empty($trial_titles)
            ? implode("|", $trial_titles)
            : "Tidak Mengikuti";

        $trial_class_waktu = !empty($trial_times)
            ? implode("|", $trial_times)
            : "-";

        /* ================================
           CAMPUS TOUR
        ================================ */

        $campus_tour = "Tidak Mengikuti";
        $campus_tour_waktu = "-";

        if ($request->campus_tour_sesi) {

            $ct = explode("|", $request->campus_tour_sesi, 2);

            $campus_tour_waktu = trim($ct[0] ?? "-");
            $campus_tour = trim($ct[1] ?? $request->campus_tour_sesi);

        }

        /* ================================
           INSERT SUPER USER
        ================================ */

        $iduser = DB::table('super_user')->insertGetId([

            'sumber_data' => 'Openhouse',
            'kegiatan' => 'Openhouse',

            'nama' => $nama,
            'hp' => $hp,
            'email' => $email,

            'password' => md5($kode),
            'kode' => $kode,

            'kelas' => $request->kelas,

            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'idkota' => $idkota,

            'sekolah' => $sekolah_input,
            'sekolah_lainnya' => $sekolah_lainnya_input,

            'jurusan_sekarang' => $request->prodi_sekarang,
            'jurusan_tujuan' => $request->prodi_tujuan,

            'jenjang_studi' => $request->jenjang_studi ?? "-",

            'campus_tour' => $campus_tour,
            'campus_tour_waktu' => $campus_tour_waktu,

            'seminar' => $seminar,
            'seminar_waktu' => $seminar_waktu,

            'trial_class' => $trial_class,
            'trial_class_waktu' => $trial_class_waktu,

            'telu_explore' => $request->telu_explore ?? "Tidak",

            'kampus' => 'Bandung',

            'kebijakan_privasi' => $request->kebijakan_privasi ?? "Tidak",

            'tahunsmb' => (date('n') >= 8) ? date('Y') + 1 : date('Y'),

            'broadcast' => 'Sudah',

            'informasi' => 'Openhouse',

            'aktivasi' => 'Y'
        ]);

        /* ================================
           KEGIATAN PESERTA
        ================================ */

        $kegiatan_list = [];

        /*
        =========================
        REGISTRASI AWAL
        =========================
        */

        $kegiatan_list[] = [

            'nama_kegiatan' =>
                'Registrasi Awal',

            'waktu_kegiatan' =>
                'Gedung Telkom University Landmark Tower Lantai 1 - Pukul 07.30 WIB'

        ];

        /*
        =========================
        SEMINAR
        =========================
        */

        if ($seminar != "Tidak Mengikuti") {

            $seminarArr =
                explode("|", $seminar);

            $seminarTimeArr =
                explode("|", $seminar_waktu);

            foreach ($seminarArr as $i => $sem) {

                $kegiatan_list[] = [

                    'nama_kegiatan' => $sem,

                    'waktu_kegiatan' =>
                        $seminarTimeArr[$i] ?? "-"

                ];

            }

        }

        /*
        =========================
        TRIAL CLASS
        =========================
        */

        if ($trial_class != "Tidak Mengikuti") {

            $trialArr =
                explode("|", $trial_class);

            $trialTimeArr =
                explode("|", $trial_class_waktu);

            foreach ($trialArr as $i => $trial) {

                $kegiatan_list[] = [

                    'nama_kegiatan' => $trial,

                    'waktu_kegiatan' =>
                        $trialTimeArr[$i] ?? "-"

                ];

            }

        }

        /*
        =========================
        CAMPUS TOUR
        =========================
        */

        if ($campus_tour != "Tidak Mengikuti") {

            $kegiatan_list[] = [

                'nama_kegiatan' =>
                    $campus_tour,

                'waktu_kegiatan' =>
                    $campus_tour_waktu

            ];

        }

        /*
        =========================
        TEL-U EXPLORE
        =========================
        */

        if (($request->telu_explore ?? "Tidak") == "Ya") {

            $kegiatan_list[] = [

                'nama_kegiatan' =>
                    'Tel-U Explore',

                'waktu_kegiatan' =>
                    'Gedung Telkom University LT 1 - Pukul 10.00 - 14.00 WIB'

            ];

        }

        /*
        =========================
        INSERT KE DATABASE
        =========================
        */

        foreach ($kegiatan_list as $k) {

            DB::table('kegiatan_peserta')->insert([

                'iduser' => $iduser,

                'nama_peserta' => $nama,

                'nama_kegiatan' =>
                    $k['nama_kegiatan'],

                'waktu_kegiatan' =>
                    $k['waktu_kegiatan']

            ]);

        }

        /* ================================
           REDIRECT
        ================================ */

        return redirect()->route('register.success');
    }
}