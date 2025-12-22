<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use App\Models\Patient;
use App\Models\Poli;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class QueueController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());
        $poli_id = $request->get('poli_id');

        $query = Queue::with(['patient', 'poli', 'doctor'])
            ->where('queue_date', $date);

        if ($poli_id) {
            $query->where('poli_id', $poli_id);
        }

        $queues = $query->orderBy('queue_number')->get();
        return response()->json($queues);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'poli_id' => 'required|exists:poli,id',
            'complaint' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $date = Carbon::today()->toDateString();

        // Cek apakah pasien sudah ada antrian hari ini untuk poli yang sama
        $existingQueue = Queue::where('patient_id', $request->patient_id)
            ->where('poli_id', $request->poli_id)
            ->where('queue_date', $date)
            ->first();

        if ($existingQueue) {
            return response()->json(['message' => 'Pasien sudah memiliki antrian untuk poli ini hari ini'], 400);
        }

        // Generate nomor antrian
        $lastQueue = Queue::where('poli_id', $request->poli_id)
            ->where('queue_date', $date)
            ->orderBy('queue_number', 'desc')
            ->first();

        $queueNumber = $lastQueue ? $lastQueue->queue_number + 1 : 1;

        // Cari dokter yang tersedia untuk poli ini hari ini
        $dayOfWeek = Carbon::now()->format('l'); // e.g., 'Monday'
        $schedule = Schedule::where('poli_id', $request->poli_id)
            ->where('hari', $dayOfWeek)
            ->where('aktif', true)
            ->first();

        $doctorId = $schedule ? $schedule->doctor_id : null;

        $queue = Queue::create([
            'patient_id' => $request->patient_id,
            'poli_id' => $request->poli_id,
            'doctor_id' => $doctorId,
            'queue_number' => $queueNumber,
            'queue_date' => $date,
            'status' => 'menunggu',
            'complaint' => $request->complaint
        ]);

        return response()->json($queue->load(['patient', 'poli', 'doctor']), 201);
    }

    public function show($id)
    {
        $queue = Queue::with(['patient', 'poli', 'doctor'])->find($id);
        if (!$queue) {
            return response()->json(['message' => 'Antrian tidak ditemukan'], 404);
        }
        return response()->json($queue);
    }

    public function update(Request $request, $id)
    {
        $queue = Queue::find($id);
        if (!$queue) {
            return response()->json(['message' => 'Antrian tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:menunggu,dipanggil,dalam_pemeriksaan,selesai,batal'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $queue->update($request->only(['status']));
        return response()->json($queue->load(['patient', 'poli', 'doctor']));
    }

    public function destroy($id)
    {
        $queue = Queue::find($id);
        if (!$queue) {
            return response()->json(['message' => 'Antrian tidak ditemukan'], 404);
        }

        $queue->delete();
        return response()->json(['message' => 'Antrian berhasil dihapus']);
    }

    public function callNext(Request $request)
    {
        $poli_id = $request->get('poli_id');
        $date = Carbon::today()->toDateString();

        $nextQueue = Queue::where('poli_id', $poli_id)
            ->where('queue_date', $date)
            ->where('status', 'menunggu')
            ->orderBy('queue_number')
            ->first();

        if (!$nextQueue) {
            return response()->json(['message' => 'Tidak ada antrian berikutnya'], 404);
        }

        $nextQueue->update(['status' => 'dipanggil']);
        return response()->json($nextQueue->load(['patient', 'poli']));
    }

    public function currentQueue(Request $request)
    {
        $poli_id = $request->get('poli_id');
        $date = Carbon::today()->toDateString();

        $current = Queue::where('poli_id', $poli_id)
            ->where('queue_date', $date)
            ->whereIn('status', ['dipanggil', 'dalam_pemeriksaan'])
            ->orderBy('updated_at', 'desc')
            ->first();

        return response()->json($current ? $current->load(['patient']) : null);
    }
}
