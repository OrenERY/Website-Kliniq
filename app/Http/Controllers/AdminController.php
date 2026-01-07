<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    private $doctors = [
        'Umum' => ['Dr. Budi Santoso', 'Dr. Siti Aminah'],
        'Gigi' => ['Drg. Ahmad Dhani', 'Drg. Raisa Andriana'],
        'Anak' => ['Dr. Seto', 'Dr. Rina'],
        'Penyakit Dalam' => ['Dr. Boyke', 'Dr. Lula'],
    ];

    public function index()
    {
        if (! session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }

        $today = now()->today();

        // Active Queues (assigned or waiting)
        $pasiens = DB::table('pasiens')
            ->whereDate('created_at', $today)
            ->where('status', '!=', 'menunggu_pembayaran')
            ->where('status', '!=', 'selesai')
            ->orderBy('id', 'asc')
            ->get();

        // Pending Payments
        $pendingPayments = DB::table('pasiens')
            ->whereDate('created_at', $today)
            ->where('status', 'menunggu_pembayaran')
            ->orderBy('created_at', 'asc')
            ->get();

        $doctors = [
            'Dr. Budi Santoso, Sp.PD',
            'Dr. Siti Aminah, Sp.A',
            'Dr. Andi Wijaya, Sp.JP',
            'Dr. Rina Kartika, Sp.M'
        ];

        return view('admin.dashboard', compact('pasiens', 'pendingPayments', 'doctors'));
    }

    public function showLoginForm()
    {
        if (session('admin_authenticated')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'pin' => 'required|string',
        ]);

        if ($request->pin === '160305') {
            session(['admin_authenticated' => true]);

            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->with('error', 'PIN Salah!');
    }

    public function logout()
    {
        session()->forget('admin_authenticated');

        return redirect()->route('landing');
    }

    public function assignDoctor(Request $request, $id)
    {
        if (! session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'doctor_name' => 'required|string',
        ]);

        DB::table('pasiens')
            ->where('id', $id)
            ->update([
                'doctor_name' => $request->doctor_name,
                'status' => 'dipanggil',
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Dokter berhasil ditugaskan!');
    }

    public function verifyPayment($id)
    {
        if (! session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }

        $patient = DB::table('pasiens')->where('id', $id)->first();
        if (!$patient) return redirect()->back()->with('error', 'Data tidak ditemukan');

        // Generate Queue Number
        $today = now()->today();
        $kode_poli = substr($patient->poli_tujuan, 0, 1);
        
        // Count existing valid queues for this poli today
        $count = DB::table('pasiens')
            ->whereDate('created_at', $today)
            ->where('poli_tujuan', $patient->poli_tujuan)
            ->where('status', '!=', 'menunggu_pembayaran')
            ->count();
        
        $nomor_antrian = strtoupper($kode_poli).'-'.sprintf('%03d', $count + 1);

        DB::table('pasiens')
            ->where('id', $id)
            ->update([
                'status' => 'menunggu',
                'status_pembayaran' => 'lunas',
                'nomor_antrian' => $nomor_antrian,
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Pembayaran Terverifikasi! Antrian Diterbitkan: ' . $nomor_antrian);
    }

    public function storeExamination(Request $request, $id)
    {
        if (! session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'diagnosa' => 'required|string',
            'tindakan' => 'nullable|string',
            'resep_obat' => 'nullable|string',
            'catatan_dokter' => 'nullable|string',
        ]);

        DB::table('pasiens')
            ->where('id', $id)
            ->update([
                'diagnosa' => $request->diagnosa,
                'tindakan' => $request->tindakan,
                'resep_obat' => $request->resep_obat,
                'catatan_dokter' => $request->catatan_dokter,
                'status' => 'selesai',
                'updated_at' => now(),
            ]);
            
        return redirect()->back()->with('success', 'Pemeriksaan Selesai. Data berhasil disimpan!');
    }
}
