<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    private $doctors = [
        'Umum' => ['Dr. Budi Santoso', 'Dr. Siti Aminah'],
        'Gigi' => ['Drg. Ahmad Dhani', 'Drg. Raisa Andriana'],
        'Anak' => ['Dr. Kak Seto', 'Dr. Ria Ricis'],
        'Penyakit Dalam' => ['Dr. Boyke', 'Dr. Lula Kamal']
    ];

    public function index()
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }

        $pasiens = DB::table('pasiens')
                    ->whereDate('created_at', Carbon::today())
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        $doctors = $this->doctors;

        return view('admin.dashboard', compact('pasiens', 'doctors'));
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
        if (!session('admin_authenticated')) {
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
}
