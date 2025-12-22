<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::all();
        return response()->json($patients);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|unique:patients|max:16',
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required',
            'no_telepon' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Generate no_rm otomatis
        $lastPatient = Patient::orderBy('id', 'desc')->first();
        $nextId = $lastPatient ? $lastPatient->id + 1 : 1;
        $no_rm = str_pad($nextId, 6, '0', STR_PAD_LEFT);

        $patient = Patient::create([
            'no_rm' => $no_rm,
            'nik' => $request->nik,
            'nama' => $request->nama,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
            'nama_kk' => $request->nama_kk,
        ]);

        return response()->json($patient, 201);
    }

    public function show($id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['message' => 'Pasien tidak ditemukan'], 404);
        }
        return response()->json($patient);
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['message' => 'Pasien tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nik' => 'required|max:16|unique:patients,nik,' . $id,
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required',
            'no_telepon' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $patient->update($request->all());
        return response()->json($patient);
    }

    public function destroy($id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['message' => 'Pasien tidak ditemukan'], 404);
        }

        $patient->delete();
        return response()->json(['message' => 'Pasien berhasil dihapus']);
    }
}