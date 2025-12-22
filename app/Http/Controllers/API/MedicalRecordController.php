<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $patient_id = $request->get('patient_id');
        $doctor_id = $request->get('doctor_id');

        $query = MedicalRecord::with(['patient', 'doctor', 'queue']);

        if ($patient_id) {
            $query->where('patient_id', $patient_id);
        }

        if ($doctor_id) {
            $query->where('doctor_id', $doctor_id);
        }

        $records = $query->orderBy('tanggal_kunjungan', 'desc')->get();
        return response()->json($records);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'queue_id' => 'required|exists:queues,id',
            'keluhan' => 'required',
            'diagnosis' => 'nullable',
            'tindakan' => 'nullable',
            'resep_obat' => 'nullable',
            'catatan' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $queue = Queue::find($request->queue_id);

        $record = MedicalRecord::create([
            'patient_id' => $queue->patient_id,
            'doctor_id' => $queue->doctor_id,
            'queue_id' => $request->queue_id,
            'tanggal_kunjungan' => Carbon::today()->toDateString(),
            'keluhan' => $request->keluhan,
            'diagnosis' => $request->diagnosis,
            'tindakan' => $request->tindakan,
            'resep_obat' => $request->resep_obat,
            'catatan' => $request->catatan
        ]);

        // Update queue status to selesai
        $queue->update(['status' => 'selesai']);

        return response()->json($record->load(['patient', 'doctor', 'queue']), 201);
    }

    public function show($id)
    {
        $record = MedicalRecord::with(['patient', 'doctor', 'queue'])->find($id);
        if (!$record) {
            return response()->json(['message' => 'Rekam medis tidak ditemukan'], 404);
        }
        return response()->json($record);
    }

    public function update(Request $request, $id)
    {
        $record = MedicalRecord::find($id);
        if (!$record) {
            return response()->json(['message' => 'Rekam medis tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'keluhan' => 'sometimes|required',
            'diagnosis' => 'nullable',
            'tindakan' => 'nullable',
            'resep_obat' => 'nullable',
            'catatan' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $record->update($request->all());
        return response()->json($record->load(['patient', 'doctor', 'queue']));
    }

    public function destroy($id)
    {
        $record = MedicalRecord::find($id);
        if (!$record) {
            return response()->json(['message' => 'Rekam medis tidak ditemukan'], 404);
        }

        $record->delete();
        return response()->json(['message' => 'Rekam medis berhasil dihapus']);
    }
}
