<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PasienController extends Controller
{
    public function index()
    {
        return view('pendaftaran');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'nik' => 'required|numeric|digits:16',
            'alamat_detail' => 'required|string',
            'kecamatan' => 'required|string',
            'poli_tujuan' => 'required|string',
        ]);

        // Generate Nomor Antrian
        $kode_poli = substr($request->poli_tujuan, 0, 1);
        $today = Carbon::today();
        
        $count = DB::table('pasiens')
                    ->whereDate('created_at', $today)
                    ->where('poli_tujuan', $request->poli_tujuan)
                    ->count();
        
        $nomor_antrian = strtoupper($kode_poli) . '-' . sprintf("%03d", $count + 1);

        $alamat_lengkap = $request->alamat_detail . ', Kec. ' . $request->kecamatan . ', Sumedang';

        DB::table('pasiens')->insert([
            'nama_pasien' => $request->nama_pasien,
            'nik' => $request->nik,
            'alamat' => $alamat_lengkap,
            'poli_tujuan' => $request->poli_tujuan,
            'nomor_antrian' => $nomor_antrian,
            'status' => 'menunggu',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('queue.show')->with('success', 'Pendaftaran Berhasil! Nomor Antrian Anda: ' . $nomor_antrian);
    }

    public function showQueue()
    {
        $antrian = DB::table('pasiens')
                    ->whereDate('created_at', Carbon::today())
                    ->orderBy('id', 'desc')
                    ->get();
        
        $current = DB::table('pasiens')
                    ->whereDate('created_at', Carbon::today())
                    ->where('status', 'dipanggil')
                    ->first();

        return view('antrian', compact('antrian', 'current'));
    }
}
