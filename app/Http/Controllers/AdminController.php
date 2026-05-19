<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        /*
        =========================
        CEK LOGIN ADMIN
        =========================
        */

        if (!session('admin_loggedin')) {

            return redirect('/admin/login');

        }

        $username = session('username');

        $role = session('role');

        /*
        =========================
        PAGE TITLE
        =========================
        */

        $pageTitle =
            ($role === 'superadmin')
            ? 'Dashboard Admin'
            : 'Dashboard Staff';

        return view('admin.index', compact(
            'username',
            'role',
            'pageTitle'
        ));
    }

    public function staffScanner()
    {
        /*
        =========================
        CEK LOGIN ADMIN
        =========================
        */

        if (!session('admin_loggedin')) {

            return redirect('/admin/login');

        }

        /*
        =========================
        KHUSUS STAFF
        =========================
        */

        if (session('role') !== 'staff') {

            return redirect('/admin');

        }

        /*
        =========================
        VIEW SCANNER STAFF
        =========================
        */

        return view('admin.staff.scanner_staff');
    }

    public function staffContent($iduser)
    {
        /*
        =========================
        CEK LOGIN STAFF
        =========================
        */

        if (!session('admin_loggedin') || session('role') !== 'staff') {

            return response(
                "<p style='color:#ff6666;text-align:center;'>Akses ditolak.</p>",
                403
            );

        }

        /*
        =========================
        CLEAN ID USER
        =========================
        */

        $iduser =
            preg_replace('/\D/', '', $iduser);

        if (!$iduser) {

            return "
                <p style='color:#ff6666;text-align:center;'>
                    ID peserta tidak valid.
                </p>
            ";

        }

        /*
        =========================
        DATA PESERTA
        =========================
        */

        $peserta = DB::table('super_user')
            ->where('iduser', $iduser)
            ->first();

        if (!$peserta) {

            return "
                <p style='color:#ff6666;text-align:center;'>
                    Data peserta tidak ditemukan.
                </p>
            ";

        }

        /*
        =========================
        DATA KEGIATAN + PRESENSI
        =========================
        */

        $kegiatan = DB::table('kegiatan_peserta as k')
            ->leftJoin('presensi_peserta as p', function ($join) {
                $join->on('k.id_kegiatan', '=', 'p.id_kegiatan')
                    ->on('k.iduser', '=', 'p.iduser');
            })
            ->where('k.iduser', $iduser)
            ->select(
                'k.id_kegiatan',
                'k.nama_kegiatan',
                'k.waktu_kegiatan',
                'p.status',
                'p.waktu_presensi'
            )
            ->orderBy('k.id_kegiatan', 'asc')
            ->get();

        /*
        =========================
        SEKOLAH
        =========================
        */

        $sekolah =
            $peserta->sekolah
            ?: ($peserta->sekolah_lainnya ?? '-');

        /*
        =========================
        HTML RESPONSE
        =========================
        */

        $html = "
            <div id='scan-content'>

                <div class='participant-info'>

                    <h3>
                        <i class='fas fa-user'></i>
                        Data Peserta
                    </h3>

                    <div class='info-grid'>

                        <div>
                            <strong>Nama:</strong>
                            " . e($peserta->nama ?? '-') . "
                        </div>

                        <div>
                            <strong>Email:</strong>
                            " . e($peserta->email ?? '-') . "
                        </div>

                        <div>
                            <strong>Sekolah:</strong>
                            " . e($sekolah) . "
                        </div>

                        <div>
                            <strong>No HP:</strong>
                            " . e($peserta->hp ?? '-') . "
                        </div>

                    </div>

                </div>

                <div class='kegiatan-list'>

                    <h3>
                        <i class='fas fa-calendar-alt'></i>
                        Daftar Kegiatan
                    </h3>

                    <table>

                        <thead>
                            <tr>
                                <th>Nama Kegiatan</th>
                                <th>Waktu</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
        ";

        if ($kegiatan->count() > 0) {

            foreach ($kegiatan as $row) {

                $status =
                    $row->status ?? 'Belum Hadir';

                $isHadir =
                    $status === 'Hadir';

                $statusClass =
                    strtolower(str_replace(' ', '-', $status));

                $idKegiatan =
                    (int) $row->id_kegiatan;

                $idUserClean =
                    (int) $iduser;

                $html .= "
                    <tr id='row-{$idKegiatan}'>

                        <td>
                            " . e($row->nama_kegiatan) . "
                        </td>

                        <td>
                            " . e($row->waktu_kegiatan) . "
                        </td>

                        <td style='text-align:center;'>

                            <span class='status {$statusClass}'>
                                " . e($status) . "
                            </span>

                        </td>

                        <td>

                            <button
                                type='button'
                                class='btn-hadir " . ($isHadir ? 'active' : '') . "'
                                onclick='toggleHadir({$idKegiatan}, {$idUserClean}, this)'>

                                <i class='fas " . ($isHadir ? 'fa-undo' : 'fa-check') . "'></i>

                                " . ($isHadir ? 'Batalkan' : 'Hadir') . "

                            </button>

                        </td>

                    </tr>
                ";
            }

        } else {

            $html .= "
                <tr>
                    <td colspan='4'
                        style='text-align:center;color:#ccc;'>
                        Belum ada kegiatan terdaftar.
                    </td>
                </tr>
            ";
        }

        $html .= "
                        </tbody>

                    </table>

                    <div style='text-align:center;margin-top:18px;'>

                        <button
                            type='button'
                            class='btn-close'
                            onclick='closeScanResult()'>

                            <i class='fas fa-times'></i>
                            Tutup

                        </button>

                    </div>

                </div>

            </div>
        ";

        return $html;
    }

    public function updatePresensi(Request $request)
    {
        /*
        =========================
        CEK LOGIN STAFF
        =========================
        */

        if (!session('admin_loggedin') || session('role') !== 'staff') {

            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.'
            ], 403);

        }

        /*
        =========================
        AMBIL DATA REQUEST
        =========================
        */

        $iduser =
            preg_replace('/\D/', '', (string) $request->input('iduser'));

        $idKegiatan =
            preg_replace('/\D/', '', (string) $request->input('id_kegiatan'));

        $action =
            $request->input('action');

        /*
        =========================
        VALIDASI
        =========================
        */

        if (!$iduser || !$idKegiatan) {

            return response()->json([
                'success' => false,
                'message' => 'Data tidak lengkap.'
            ]);

        }

        /*
        =========================
        AMBIL DATA USER
        =========================
        */

        $user = DB::table('super_user')
            ->where('iduser', $iduser)
            ->first();

        /*
        =========================
        AMBIL DATA KEGIATAN
        =========================
        */

        $kegiatan = DB::table('kegiatan_peserta')
            ->where('id_kegiatan', $idKegiatan)
            ->where('iduser', $iduser)
            ->first();

        if (!$user || !$kegiatan) {

            return response()->json([
                'success' => false,
                'message' => 'Data peserta atau kegiatan tidak ditemukan.'
            ]);

        }

        /*
        =========================
        STATUS PRESENSI
        =========================
        */

        if ($action === 'hadir') {

            $status = 'Hadir';

            $waktuPresensi = now();

        } else {

            $status = 'Belum Hadir';

            $waktuPresensi = null;

        }

        /*
        =========================
        CEK DATA PRESENSI
        =========================
        */

        $exists = DB::table('presensi_peserta')
            ->where('iduser', $iduser)
            ->where('id_kegiatan', $idKegiatan)
            ->exists();

        /*
        =========================
        UPDATE / INSERT
        =========================
        */

        if ($exists) {

            DB::table('presensi_peserta')
                ->where('iduser', $iduser)
                ->where('id_kegiatan', $idKegiatan)
                ->update([
                    'status' => $status,
                    'waktu_presensi' => $waktuPresensi
                ]);

        } else {

            DB::table('presensi_peserta')
                ->insert([
                    'iduser' => $iduser,
                    'nama' => $user->nama ?? '-',
                    'email' => $user->email ?? '-',
                    'nama_kegiatan' => $kegiatan->nama_kegiatan,
                    'id_kegiatan' => $idKegiatan,
                    'waktu_presensi' => $waktuPresensi,
                    'status' => $status
                ]);

        }

        return response()->json([
            'success' => true,
            'message' => 'Status presensi berhasil diperbarui.',
            'status' => $status
        ]);


    }

    /*
|--------------------------------------------------------------------------
| STAFF DASHBOARD API - DATA KEGIATAN PER SESI
|--------------------------------------------------------------------------
*/

    public function getKegiatanBySesi(Request $request)
    {
        $sesi = $request->query('sesi');

        $kegiatanData = config('kegiatan');

        if (!$sesi || !isset($kegiatanData[$sesi])) {
            return response()->json([]);
        }

        return response()->json($kegiatanData[$sesi]);
    }

    /*
    |--------------------------------------------------------------------------
    | STAFF DASHBOARD API - SUMMARY PER KEGIATAN
    |--------------------------------------------------------------------------
    */

    public function getSummaryKegiatan(Request $request)
    {
        $nama = $request->query('nama');

        if (!$nama) {
            return response()->json([
                'totalPeserta' => 0,
                'hadir' => 0,
                'tidakHadir' => 0,
            ]);
        }

        $totalPeserta = DB::table('kegiatan_peserta')
            ->where('nama_kegiatan', 'LIKE', '%' . $nama . '%')
            ->count();

        $hadir = DB::table('presensi_peserta')
            ->where('nama_kegiatan', 'LIKE', '%' . $nama . '%')
            ->where('status', 'Hadir')
            ->count();

        $tidakHadir = max(0, $totalPeserta - $hadir);

        return response()->json([
            'totalPeserta' => (int) $totalPeserta,
            'hadir' => (int) $hadir,
            'tidakHadir' => (int) $tidakHadir,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STAFF DASHBOARD API - SUMMARY PER SESI
    |--------------------------------------------------------------------------
    */

    public function getSummarySesi(Request $request)
    {
        $sesi = $request->query('sesi');

        $kegiatanData = config('kegiatan');

        if (!$sesi || !isset($kegiatanData[$sesi])) {
            return response()->json([
                'totalPeserta' => 0,
                'hadir' => 0,
                'tidakHadir' => 0,
            ]);
        }

        $listKegiatan = $kegiatanData[$sesi];

        $totalPeserta = 0;
        $hadir = 0;

        foreach ($listKegiatan as $kg) {

            $totalPeserta += DB::table('kegiatan_peserta')
                ->where('nama_kegiatan', 'LIKE', '%' . $kg . '%')
                ->count();

            $hadir += DB::table('presensi_peserta')
                ->where('nama_kegiatan', 'LIKE', '%' . $kg . '%')
                ->where('status', 'Hadir')
                ->count();

        }

        $tidakHadir = max(0, $totalPeserta - $hadir);

        return response()->json([
            'totalPeserta' => (int) $totalPeserta,
            'hadir' => (int) $hadir,
            'tidakHadir' => (int) $tidakHadir,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STAFF DASHBOARD API - HADIR OC PER KEGIATAN
    |--------------------------------------------------------------------------
    | Maksud lama:
    | Ambil peserta yang daftar kegiatan X,
    | lalu hitung berapa dari mereka yang sudah hadir Registrasi Awal.
    |--------------------------------------------------------------------------
    */

    public function getHadirOcKegiatan(Request $request)
    {
        $nama = $request->query('nama');

        if (!$nama) {
            return response()->json([
                'hadirOC' => 0,
            ]);
        }

        $ids = DB::table('kegiatan_peserta')
            ->where('nama_kegiatan', $nama)
            ->distinct()
            ->pluck('iduser')
            ->filter()
            ->values();

        if ($ids->isEmpty()) {
            return response()->json([
                'hadirOC' => 0,
            ]);
        }

        $hadirOC = DB::table('presensi_peserta')
            ->where('nama_kegiatan', 'Registrasi Awal')
            ->where('status', 'Hadir')
            ->whereIn('iduser', $ids)
            ->distinct('iduser')
            ->count('iduser');

        return response()->json([
            'hadirOC' => (int) $hadirOC,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STAFF DASHBOARD API - HADIR OC PER SESI
    |--------------------------------------------------------------------------
    */

    public function getHadirOcSesi(Request $request)
    {
        $sesi = $request->query('sesi');

        $kegiatanData = config('kegiatan');

        if (!$sesi || !isset($kegiatanData[$sesi])) {
            return response()->json([
                'hadirOC' => 0,
            ]);
        }

        $listKegiatan = $kegiatanData[$sesi];

        $ids = collect();

        foreach ($listKegiatan as $kg) {

            $foundIds = DB::table('kegiatan_peserta')
                ->where('nama_kegiatan', 'LIKE', '%' . $kg . '%')
                ->distinct()
                ->pluck('iduser');

            $ids = $ids->merge($foundIds);

        }

        $ids = $ids
            ->filter()
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return response()->json([
                'hadirOC' => 0,
            ]);
        }

        $hadirOC = DB::table('presensi_peserta')
            ->where('nama_kegiatan', 'LIKE', '%Registrasi Awal%')
            ->where('status', 'Hadir')
            ->whereIn('iduser', $ids)
            ->distinct('iduser')
            ->count('iduser');

        return response()->json([
            'hadirOC' => (int) $hadirOC,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STAFF DASHBOARD API - DEFAULT SUMMARY
    |--------------------------------------------------------------------------
    | Buat 3 box awal:
    | Total Pendaftar, Hadir Registrasi Awal, Hadir Kegiatan
    |--------------------------------------------------------------------------
    */

    public function getStaffSummary()
    {
        $totalPendaftar = DB::table('super_user')
            ->where('sumber_data', 'Openhouse')
            ->count();

        $hadirRegistrasi = DB::table('presensi_peserta')
            ->where('nama_kegiatan', 'LIKE', '%Registrasi Awal%')
            ->where('status', 'Hadir')
            ->distinct('iduser')
            ->count('iduser');

        $hadirKegiatan = DB::table('presensi_peserta')
            ->where('nama_kegiatan', 'NOT LIKE', '%Registrasi Awal%')
            ->where('status', 'Hadir')
            ->count();

        return response()->json([
            'totalPendaftar' => (int) $totalPendaftar,
            'hadirRegistrasi' => (int) $hadirRegistrasi,
            'hadirKegiatan' => (int) $hadirKegiatan,
        ]);
    }

    // admin super

    public function superContent(Request $request)
    {
        $limit = 10;

        $page = (int) $request->input('page', 1);
        $page = max($page, 1);

        $offset = ($page - 1) * $limit;

        $type = $request->query('type', '');

        $userRole = session('role', '');

        $title = '';
        $editable = false;
        $rows = collect();
        $totalData = 0;
        $totalPage = 1;

        $extraHtml = '';
        $tableHtml = '';
        $afterHtml = '';

        switch ($type) {

            /*
            |--------------------------------------------------------------------------
            | PESERTA
            |--------------------------------------------------------------------------
            */

            case 'peserta':

                $title = "<i class='fas fa-user-graduate'></i> Data Peserta Open House";
                $editable = false;

                $kelasRows = DB::table('super_user')
                    ->selectRaw("
                    COALESCE(NULLIF(kelas, ''), 'Tidak Diketahui') AS kelas,
                    COUNT(*) AS jumlah
                ")
                    ->groupBy('kelas')
                    ->orderByDesc('jumlah')
                    ->get();

                $extraHtml .= "
                <h3 style='margin-top:25px;color:#ff6666;'>
                    <i class='fas fa-chart-bar'></i>
                    Distribusi Peserta Berdasarkan Profesi / Kelas
                </h3>
            ";

                if ($kelasRows->count() > 0) {

                    $extraHtml .= "
                    <table style='width:100%;margin-top:10px;border-collapse:collapse;color:#fff;'>
                        <thead style='background:rgba(255,0,0,0.2);'>
                            <tr>
                                <th style='padding:10px;'>No</th>
                                <th style='padding:10px;'>Profesi / Kelas</th>
                                <th style='padding:10px;text-align:right;'>Jumlah Peserta</th>
                            </tr>
                        </thead>
                        <tbody>
                ";

                    $no = 1;

                    foreach ($kelasRows as $row) {

                        $kelas = e($row->kelas);
                        $jumlah = e($row->jumlah);

                        $extraHtml .= "
                        <tr>
                            <td style='padding:8px;'>{$no}</td>
                            <td style='padding:8px;'>{$kelas}</td>
                            <td style='padding:8px;text-align:right;'>
                                <i class='fas fa-user'></i>
                                {$jumlah}
                            </td>
                        </tr>
                    ";

                        $no++;
                    }

                    $extraHtml .= "
                        </tbody>
                    </table>
                ";
                }

                $rows = DB::table('super_user')
                    ->select([
                        'nama as Nama Lengkap',
                        'email as Email',
                        'hp as No. WhatsApp',
                        'kelas as Profesi / Kelas',
                        DB::raw("
                        CASE
                            WHEN sekolah IS NULL OR sekolah = ''
                            THEN sekolah_lainnya
                            ELSE sekolah
                        END AS `Asal Sekolah`
                    "),
                        'provinsi as Provinsi',
                        'kota as Kota',
                        'createdAt as Tanggal Daftar',
                    ])
                    ->orderByDesc('createdAt')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();

                $totalData = DB::table('super_user')->count();
                $totalPage = (int) ceil($totalData / $limit);

                $extraHtml .= "
                <form method='post'
                    action='#'
                    style='margin:10px 0 20px 0;'>

                    <button type='button'
                        class='btn-add'
                        style='background:linear-gradient(90deg,#0f9d58,#0b8043);'
                        onclick=\"alert('Export Excel nanti kita sambungkan ke Laravel')\">

                        <i class='fas fa-file-excel'></i>
                        Export Excel

                    </button>

                </form>
            ";

                break;

            /*
            |--------------------------------------------------------------------------
            | BOOTH
            |--------------------------------------------------------------------------
            */

            case 'booth':

                $title = "<i class='fas fa-store'></i> Data Booth Aktif";
                $editable = ($userRole === 'superadmin');

                $rows = DB::table('booth')
                    ->select([
                        'idbooth',
                        'nama_booth as Nama Booth',
                        'kategori as Kategori',
                        'lantai as Lantai',
                        'qr_code as QR Code',
                    ])
                    ->orderBy('idbooth')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();

                $totalData = DB::table('booth')->count();
                $totalPage = (int) ceil($totalData / $limit);

                break;

            /*
            |--------------------------------------------------------------------------
            | STAFF
            |--------------------------------------------------------------------------
            */

            case 'staff':

                $title = "<i class='fas fa-users-cog'></i> Data Staff & Admin";
                $editable = true;

                $rows = DB::table('admin_user')
                    ->select([
                        'id_admin',
                        'nama_lengkap as Nama Lengkap',
                        'username as Username',
                        'role as Role',
                        'last_login as Terakhir Login',
                    ])
                    ->orderByRaw("CASE WHEN role = 'superadmin' THEN 0 ELSE 1 END")
                    ->orderBy('nama_lengkap')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();

                $totalData = DB::table('admin_user')->count();
                $totalPage = (int) ceil($totalData / $limit);

                break;

            /*
            |--------------------------------------------------------------------------
            | KUNJUNGAN
            |--------------------------------------------------------------------------
            */

            case 'kunjungan':

                $title = "<i class='fas fa-handshake'></i> Data Kunjungan Booth";
                $editable = false;

                $rows = DB::table('booth_kunjungan')
                    ->select([
                        'nama_peserta as Nama Peserta',
                        'nama_booth as Booth',
                        'kategori as Kategori',
                        'waktu_kunjungan as Waktu Kunjungan',
                    ])
                    ->orderByDesc('waktu_kunjungan')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();

                $totalData = DB::table('booth_kunjungan')->count();
                $totalPage = (int) ceil($totalData / $limit);

                break;

            /*
            |--------------------------------------------------------------------------
            | OPENING / REGISTRASI AWAL
            |--------------------------------------------------------------------------
            */

            case 'opening':

                $title = "<i class='fas fa-user-graduate'></i> Data Peserta Bersedia Hadir Opening Open House";
                $editable = false;

                $rows = DB::table('super_user')
                    ->select([
                        'nama as Nama Lengkap',
                        'email as Email',
                        'hp as No. WhatsApp',
                        'kelas as Profesi / Kelas',
                        'provinsi as Provinsi',
                        'kota as Kota',
                        'sekolah as Sekolah/Instansi',
                        'sekolah_lainnya as Sekolah/Instansi Lainnya',
                        'createdAt as Tanggal Daftar',
                        'ikut_opening as Hadir Opening',
                    ])
                    ->where('ikut_opening', 'Ya')
                    ->orderByDesc('createdAt')
                    ->get();

                $totalData = $rows->count();
                $totalPage = 1;

                break;

            /*
            |--------------------------------------------------------------------------
            | KEGIATAN PESERTA
            |--------------------------------------------------------------------------
            */

            case 'kegiatan_peserta':

                $title = "<i class='fas fa-clipboard-list'></i> Manajemen Kegiatan Peserta";
                $editable = false;

                /*
                |--------------------------------------------------------------------------
                | SUMMARY SESI 1 - 4
                |--------------------------------------------------------------------------
                */

                $sesiSummary = [];

                for ($i = 1; $i <= 4; $i++) {

                    if ($i == 1) {

                        $where = "
                        nama_kegiatan LIKE '%Fakultas Informatika%'
                        OR nama_kegiatan LIKE '%Teknik Elektro%'
                        OR nama_kegiatan LIKE '%Empathy%'
                        OR nama_kegiatan LIKE '%Parent%'
                    ";

                    } elseif ($i == 2) {

                        $where = "
                        nama_kegiatan LIKE '%Rekayasa Industri%'
                        OR nama_kegiatan LIKE '%Ilmu Terapan%'
                        OR nama_kegiatan LIKE '%Smart Health%'
                        OR nama_kegiatan LIKE '%Data Sains%'
                    ";

                    } elseif ($i == 3) {

                        $where = "
                        nama_kegiatan LIKE '%Ekonomi Bisnis%'
                        OR nama_kegiatan LIKE '%Industri Kreatif%'
                        OR nama_kegiatan LIKE '%Robot Mini%'
                        OR nama_kegiatan LIKE '%Tech Meets%'
                    ";

                    } else {

                        $where = "
                        nama_kegiatan LIKE '%Komunikasi%'
                        OR nama_kegiatan LIKE '%AI%'
                        OR nama_kegiatan LIKE '%Leisure%'
                        OR nama_kegiatan LIKE '%Logistics%'
                    ";

                    }

                    $data = DB::table('kegiatan_peserta')
                        ->selectRaw("
                        COUNT(*) AS total,
                        SUM(CASE WHEN nama_kegiatan LIKE '%Seminar%' THEN 1 ELSE 0 END) AS seminar,
                        SUM(CASE WHEN nama_kegiatan LIKE '%Trial%' THEN 1 ELSE 0 END) AS trial,
                        MIN(waktu_kegiatan) AS waktu
                    ")
                        ->whereRaw("({$where})")
                        ->first();

                    $sesiSummary[$i] = [
                        'total' => (int) ($data->total ?? 0),
                        'seminar' => (int) ($data->seminar ?? 0),
                        'trial' => (int) ($data->trial ?? 0),
                        'waktu' => $data->waktu ?? '-',
                    ];
                }

                /*
                |--------------------------------------------------------------------------
                | SUMMARY CAMPUS TOUR 1 - 5
                |--------------------------------------------------------------------------
                */

                $tourSummary = [];

                for ($i = 1; $i <= 5; $i++) {

                    $tour = DB::table('kegiatan_peserta')
                        ->selectRaw("
                        COUNT(*) AS total,
                        MIN(waktu_kegiatan) AS waktu
                    ")
                        ->where('nama_kegiatan', 'LIKE', '%Campus Tour%')
                        ->where('nama_kegiatan', 'LIKE', "%Sesi {$i}%")
                        ->first();

                    $tourSummary[$i] = [
                        'total' => (int) ($tour->total ?? 0),
                        'waktu' => $tour->waktu ?? '-',
                    ];
                }

                $extraHtml .= "
                <style>
                    .summary-grid{
                        display:grid;
                        grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
                        gap:15px;
                        margin:20px 0;
                    }

                    .summary-box{
                        background:linear-gradient(145deg,rgba(255,0,0,.25),rgba(0,0,0,.2));
                        border:1px solid rgba(255,0,0,.3);
                        border-radius:12px;
                        padding:16px;
                        color:#fff;
                        text-align:left;
                        box-shadow:0 0 12px rgba(255,0,0,.2);
                        transition:.3s;
                    }

                    .summary-box:hover{
                        transform:scale(1.03);
                        box-shadow:0 0 18px rgba(255,0,0,.4);
                    }

                    .summary-box h3{
                        color:#ff4d4d;
                        font-size:18px;
                        margin:0 0 8px;
                        font-weight:700;
                    }

                    .summary-box p{
                        margin:4px 0;
                        font-size:14px;
                        color:#ddd;
                    }
                </style>

                <div class='summary-grid'>
            ";

                for ($i = 1; $i <= 4; $i++) {

                    $extraHtml .= "
                    <div class='summary-box' id='summary-sesi{$i}'>
                        <h3>Sesi {$i}</h3>
                        <p class='total'>Total Pendaftar: {$sesiSummary[$i]['total']}</p>
                        <p class='seminar'>Seminar: {$sesiSummary[$i]['seminar']}</p>
                        <p class='trial'>Trial Class: {$sesiSummary[$i]['trial']}</p>
                        <p class='waktu'>Waktu: {$sesiSummary[$i]['waktu']}</p>
                    </div>
                ";
                }

                $extraHtml .= "
                </div>

                <div class='tour-grid'>
            ";

                for ($i = 1; $i <= 5; $i++) {

                    $extraHtml .= "
                    <div class='tour-box'>
                        <h3>Campus Tour - Sesi {$i}</h3>
                        <p class='total'>Total Peserta: {$tourSummary[$i]['total']}</p>
                        <p class='waktu'>Waktu: {$tourSummary[$i]['waktu']}</p>
                    </div>
                ";
                }

                $extraHtml .= "
                </div>

                <div class='filter-container'>
                    <div class='filter-box'>

                        <div class='filter-label'>
                            <i class='fas fa-filter'></i>
                            <span>Filter Berdasarkan:</span>
                        </div>

                        <select id='filterSesi'>
                            <option value='all'>Semua Sesi</option>
                            <option value='1'>Sesi 1</option>
                            <option value='2'>Sesi 2</option>
                            <option value='3'>Sesi 3</option>
                            <option value='4'>Sesi 4</option>
                        </select>

                        <select id='filterKegiatan' disabled>
                            <option value='all'>Semua Kegiatan</option>
                        </select>

                        <button type='button'
                            id='applyFilter'
                            class='btn-filter'>

                            <i class='fas fa-search'></i>
                            Terapkan Filter

                        </button>

                    </div>
                </div>

                <div id='filterSummary'
                    style='margin-top:10px;color:#aaa;font-size:14px;'>
                </div>
            ";

                $allKegiatanRows = DB::table('kegiatan_peserta')
                    ->select([
                        'id_kegiatan',
                        'iduser',
                        'nama_peserta as Nama Peserta',
                        'nama_kegiatan as Nama Kegiatan',
                        'waktu_kegiatan as Waktu Kegiatan',
                    ])
                    ->orderByDesc('waktu_kegiatan')
                    ->get();

                $jsRows = [];

                foreach ($allKegiatanRows as $r) {

                    $namaKegiatan = $r->{'Nama Kegiatan'} ?? '';

                    $namaKegiatan = preg_replace('/^Trial Class\s*-\s*/i', '', $namaKegiatan);
                    $namaKegiatan = preg_replace('/^Seminar\s*-\s*/i', '', $namaKegiatan);
                    $namaKegiatan = trim(preg_replace('/\s+/', ' ', $namaKegiatan));

                    $jsRows[] = [
                        'id_kegiatan' => $r->id_kegiatan,
                        'iduser' => $r->iduser,
                        'Nama Peserta' => $r->{'Nama Peserta'},
                        'Nama Kegiatan' => $namaKegiatan,
                        'Waktu Kegiatan' => $r->{'Waktu Kegiatan'},
                    ];
                }

                $tableHtml .= "
                <table>
                    <thead>
                        <tr>
                            <th>id_kegiatan</th>
                            <th>iduser</th>
                            <th>Nama Peserta</th>
                            <th>Nama Kegiatan</th>
                            <th>Waktu Kegiatan</th>
                        </tr>
                    </thead>

                    <tbody id='kegiatan-body'>
                    </tbody>
                </table>

                <div id='kegiatan-pagination'
                    class='pagination'
                    style='margin-top:20px;'>
                </div>

                <script>
                    window._kegiatanPesertaData = " . json_encode($jsRows, JSON_UNESCAPED_UNICODE) . ";
                </script>

                <script src='" . asset('js/admin/super/kegiatanData.js') . "' defer></script>
                <script src='" . asset('js/admin/super/pagination_kegiatan.js') . "' defer></script>
                <script src='" . asset('js/admin/super/filter_kegiatan.js') . "' defer></script>
            ";

                $totalPage = 1;

                break;

            /*
            |--------------------------------------------------------------------------
            | REWARD CONFIG
            |--------------------------------------------------------------------------
            */

            case 'reward_config':

                $title = "<i class='fas fa-gift'></i> Pengaturan Target Reward";
                $editable = ($userRole === 'superadmin');

                $config = config('reward');

                $facultyTarget = $config['facultyTarget'] ?? 7;
                $otherTarget = $config['otherTarget'] ?? 2;

                $modeText = ((int) $otherTarget > 0)
                    ? "<span style='color:#00ff99;'>Mode Normal Fakultas + Lainnya</span>"
                    : "<span style='color:#66ccff;'>Mode Fakultas Saja</span>";

                return response("
                <h2 style='display:flex;align-items:center;gap:10px;'>{$title}</h2>

                <div style='margin:25px auto;max-width:420px;background:rgba(255,255,255,0.05);padding:20px;border-radius:10px;'>

                    <form method='POST'
                        action='#'
                        onsubmit=\"event.preventDefault(); alert('Update reward config nanti kita sambungkan ke Laravel');\">

                        <input type='hidden'
                            name='_token'
                            value='" . csrf_token() . "'>

                        <label style='display:block;margin-bottom:6px;color:#ccc;'>
                            🎓 Target Booth Fakultas
                        </label>

                        <input type='number'
                            name='facultyTarget'
                            value='{$facultyTarget}'
                            min='0'
                            required
                            style='width:100%;padding:10px;border-radius:8px;border:none;background:#222;color:#fff;margin-bottom:12px;'>

                        <label style='display:block;margin-bottom:6px;color:#ccc;'>
                            🏪 Target Booth Lainnya
                        </label>

                        <input type='number'
                            name='otherTarget'
                            value='{$otherTarget}'
                            min='0'
                            required
                            style='width:100%;padding:10px;border-radius:8px;border:none;background:#222;color:#fff;margin-bottom:20px;'>

                        <button type='submit'
                            class='btn-add'
                            style='float:none;width:100%;'>

                            <i class='fas fa-save'></i>
                            Simpan Perubahan

                        </button>

                    </form>

                </div>

                <p style='text-align:center;margin-top:10px;color:#aaa;'>
                    Status saat ini: {$modeText}
                </p>
            ");

            default:

                return response("
                <p style='color:#999;text-align:center;'>
                    Tidak ada data.
                </p>
            ");
        }

        /*
        |--------------------------------------------------------------------------
        | HEADER TITLE
        |--------------------------------------------------------------------------
        */

        $html = "";

        $html .= $extraHtml;

        $html .= "
        <h2 style='display:flex;align-items:center;gap:10px;'>
            {$title}
        </h2>
    ";

        /*
        |--------------------------------------------------------------------------
        | TOMBOL TAMBAH
        |--------------------------------------------------------------------------
        */

        if ($editable) {

            if ($type === 'staff') {

                $html .= "
                <button class='btn-add'
                    onclick=\"openForm('add')\">

                    <i class='fas fa-plus-circle'></i>
                    Tambah Admin/Staff

                </button>
            ";

            } elseif ($type === 'booth') {

                $html .= "
                <button class='btn-add'
                    onclick=\"openBoothForm('add')\">

                    <i class='fas fa-plus-circle'></i>
                    Tambah Booth

                </button>
            ";
            }
        }

        /*
        |--------------------------------------------------------------------------
        | TABLE KHUSUS KEGIATAN PESERTA
        |--------------------------------------------------------------------------
        */

        if ($type === 'kegiatan_peserta') {

            $html .= $tableHtml;

        } else {

            if ($rows->count() > 0) {

                $firstRow = (array) $rows->first();

                $html .= "
                <table>
                    <thead>
                        <tr>
            ";

                foreach (array_keys($firstRow) as $fieldName) {

                    if (
                        $editable
                        &&
                        in_array($fieldName, ['id_admin', 'idbooth'])
                    ) {
                        continue;
                    }

                    $html .= "
                    <th>" . e($fieldName) . "</th>
                ";
                }

                if ($editable) {

                    $html .= "
                    <th style='text-align:center;'>
                        Aksi
                    </th>
                ";
                }

                $html .= "
                        </tr>
                    </thead>
                    <tbody>
            ";

                foreach ($rows as $rowObj) {

                    $row = (array) $rowObj;

                    $role = strtolower($row['Role'] ?? '');
                    $rowClass = $role === 'superadmin'
                        ? 'superadmin-row'
                        : '';

                    $html .= "
                    <tr class='{$rowClass}'>
                ";

                    foreach ($row as $key => $value) {

                        if (
                            $editable
                            &&
                            in_array($key, ['id_admin', 'idbooth'])
                        ) {
                            continue;
                        }

                        $html .= "
                        <td>" . e($value ?: '-') . "</td>
                    ";
                    }

                    if ($editable) {

                        if ($type === 'staff') {

                            $idAdmin = e($row['id_admin']);
                            $namaLengkap = e($row['Nama Lengkap']);
                            $username = e($row['Username']);
                            $roleValue = e($row['Role']);

                            $html .= "
                            <td style='text-align:center;'>

                                <button class='btn-action edit'
                                    onclick=\"openForm('edit','{$idAdmin}','{$namaLengkap}','{$username}','','{$roleValue}')\">

                                    <i class='fas fa-edit'></i>

                                </button>

                                <button class='btn-action delete'
                                    onclick=\"deleteUser('{$idAdmin}')\">

                                    <i class='fas fa-trash-alt'></i>

                                </button>

                            </td>
                        ";

                        } elseif ($type === 'booth') {

                            $idBooth = e($row['idbooth']);
                            $namaBooth = e($row['Nama Booth']);
                            $kategori = e($row['Kategori']);
                            $lantai = e($row['Lantai']);

                            $html .= "
                            <td style='text-align:center;'>

                                <button class='btn-action edit'
                                    onclick=\"openBoothForm('edit','{$idBooth}','{$namaBooth}','{$kategori}','{$lantai}')\">

                                    <i class='fas fa-edit'></i>

                                </button>

                                <button class='btn-action delete'
                                    onclick=\"deleteBooth('{$idBooth}')\">

                                    <i class='fas fa-trash-alt'></i>

                                </button>

                                <button class='btn-action qr'
    onclick=\"window.open('/admin/super/generate-qr/{$idBooth}', '_blank')\">
    <i class='fas fa-qrcode'></i>
</button>

                            </td>
                        ";
                        }
                    }

                    $html .= "
                    </tr>
                ";
                }

                $html .= "
                    </tbody>
                </table>
            ";

            } else {

                $html .= "
                <p style='text-align:center;color:#888;'>
                    Belum ada data pada kategori ini.
                </p>
            ";
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        if (isset($totalPage) && $totalPage > 1) {

            $html .= "
            <div class='pagination'>
        ";

            for ($i = 1; $i <= $totalPage; $i++) {

                $active = ($i == $page)
                    ? 'active'
                    : '';

                $html .= "
                <button class='page-btn {$active}'
                    data-page='{$i}'
                    data-type='{$type}'>

                    {$i}

                </button>
            ";
            }

            $html .= "
            </div>

            <style>
                .pagination {
                    margin-top: 20px;
                    display: flex;
                    gap: 8px;
                    flex-wrap: wrap;
                }

                .page-btn {
                    padding: 6px 12px;
                    background: #222;
                    border: 1px solid #555;
                    color: #fff;
                    border-radius: 6px;
                    cursor: pointer;
                }

                .page-btn.active {
                    background: #ff4545;
                    border-color: #ff6b6b;
                }

                .page-btn:hover {
                    background: #444;
                }
            </style>
        ";
        }

        $html .= "
        <link rel='stylesheet'
            href='" . asset('css/admin/super/content.css') . "'>

        <script src='" . asset('js/admin/super/pagination.js') . "' defer></script>
    ";

        if ($type === 'booth') {

            $html .= "
            <script src='" . asset('js/admin/super/booth.js') . "' defer></script>
        ";

        } elseif ($type === 'staff') {

            $html .= "
            <script src='" . asset('js/admin/super/staff.js') . "' defer></script>
        ";
        }

        return response($html);
    }
public function generateQrBooth($idbooth)
{
    $booth = DB::table('booth')
        ->where('idbooth', $idbooth)
        ->first();

    if (!$booth) {

        abort(404);

    }

    /*
    =========================
    DATA QR
    =========================
    */

    $qrData = json_encode([
        'idbooth' => $booth->idbooth,
        'nama_booth' => $booth->nama_booth,
    ]);

    /*
    =========================
    URL QR EXTERNAL
    =========================
    */

    $qrUrl =
        'https://quickchart.io/qr?size=400&text='
        . urlencode($qrData);

    /*
    =========================
    SIMPAN URL QR
    =========================
    */

    DB::table('booth')
        ->where('idbooth', $idbooth)
        ->update([
            'qr_code' => $qrUrl
        ]);

    /*
    =========================
    REDIRECT KE QR
    =========================
    */

    return redirect($qrUrl);
}
}


