<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['doctor', 'poli'])->get();
        return response()->json($schedules);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,id',
            'poli_id' => 'required|exists:poli,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'kuota_pasien' => 'required|integer|min:1',
            'aktif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $schedule = Schedule::create($request->all());
        return response()->json($schedule->load(['doctor', 'poli']), 201);
    }

    public function show($id)
    {
        $schedule = Schedule::with(['doctor', 'poli'])->find($id);
        if (!$schedule) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }
        return response()->json($schedule);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::find($id);
        if (!$schedule) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'doctor_id' => 'sometimes|required|exists:doctors,id',
            'poli_id' => 'sometimes|required|exists:poli,id',
            'hari' => 'sometimes|required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'waktu_mulai' => 'sometimes|required|date_format:H:i',
            'waktu_selesai' => 'sometimes|required|date_format:H:i|after:waktu_mulai',
            'kuota_pasien' => 'sometimes|required|integer|min:1',
            'aktif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $schedule->update($request->all());
        return response()->json($schedule->load(['doctor', 'poli']));
    }

    public function destroy($id)
    {
        $schedule = Schedule::find($id);
        if (!$schedule) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        $schedule->delete();
        return response()->json(['message' => 'Jadwal berhasil dihapus']);
    }
}
