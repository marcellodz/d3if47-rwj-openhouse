<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    /* ================= PROVINSI ================= */

    public function getProvinsi()
    {
        return response()->json(
            DB::table('porsi_sma')
                ->select('provinsi')
                ->distinct()
                ->orderBy('provinsi')
                ->pluck('provinsi')
        );
    }

    /* ================= KOTA ================= */

    public function getKota($provinsi)
    {
        return response()->json(
            DB::table('porsi_sma')
                ->where('provinsi', urldecode($provinsi))
                ->select('kota')
                ->distinct()
                ->orderBy('kota')
                ->pluck('kota')
        );
    }

    /* ================= SEKOLAH ================= */

    public function getSekolah($kota)
    {
        return response()->json(
            DB::table('porsi_sma')
                ->where('kota', urldecode($kota))
                ->select('namasma')
                ->distinct()
                ->orderBy('namasma')
                ->pluck('namasma')
        );
    }

    /* ================= FAKULTAS ================= */

    public function getFakultas(Request $request)
    {
        $type = $request->query('type');

        $query = DB::table('programstudi');

        if (in_array($type, ['Mahasiswa', 'Fresh Graduate', 'Guru'])) {

            $query->whereIn('status', ['Regular', 'Pasca', 'Ekstensi', 'Lanjutan']);

        } elseif ($type === 'Dosen') {

            $query->whereIn('status', ['Pasca', 'Doktoral']);

        }

        return response()->json(
            $query->whereNotIn('fakultas', ['', '-'])
                ->select('fakultas')
                ->distinct()
                ->orderBy('fakultas')
                ->pluck('fakultas')
        );
    }

    /* ================= RUMPUN ================= */

    public function getRumpun($fakultas)
    {
        return response()->json(
            DB::table('programstudi')
                ->where('fakultas', urldecode($fakultas))
                ->whereNotIn('rumpun', ['', '-'])
                ->select('rumpun')
                ->distinct()
                ->orderBy('rumpun')
                ->pluck('rumpun')
        );
    }

    /* ================= PRODI ================= */

    public function getProdi(Request $request)
    {
        $type = $request->query('type');
        $fakultas = $request->query('fakultas');
        $rumpun = $request->query('rumpun');

        $query = DB::table('programstudi')
            ->where('fakultas', $fakultas)
            ->where('rumpun', $rumpun);

        if (in_array($type, ['Mahasiswa', 'Fresh Graduate', 'Guru'])) {

            $query->whereIn('status', ['Regular', 'Pasca', 'Ekstensi', 'Lanjutan']);

        } elseif ($type === 'Dosen') {

            $query->whereIn('status', ['Pasca', 'Doktoral']);

        }

        return response()->json(
            $query->whereNotIn('namaprodi', ['', '-'])
                ->orderBy('namaprodi')
                ->pluck('namaprodi')
        );
    }

    /* ================= PRODI BERDASARKAN JENJANG ================= */
public function getProgramStudi(Request $request)
{
    $jenjang = strtoupper(trim($request->query('jenjang')));

    $query = DB::table('programstudi');

    switch ($jenjang) {

        case 'D3':
            $query->where('status', 'Pindahan');
            break;

        case 'S1':
            $query->where('status', 'Pasca');
            break;

        case 'S2':
            $query->where('status', 'Doktoral');
            break;

        default:

            // 🔥 fallback untuk "Lainnya"
            $query->whereIn('status', ['Regular', 'Ekstensi']);

            break;
    }

    return response()->json(
        $query->whereNotIn('namaprodi', ['', '-'])
            ->select('namaprodi')
            ->distinct()
            ->orderBy('namaprodi')
            ->pluck('namaprodi')
    );
}

    /* ================= KEGIATAN ================= */

    public function getKegiatanLimit()
    {
        return response()->json(
            DB::table('kegiatan')
                ->select('nama_kegiatan', 'total_pendaftar', 'limit_total', 'status')
                ->get()
                ->keyBy('nama_kegiatan')
        );
    }
}