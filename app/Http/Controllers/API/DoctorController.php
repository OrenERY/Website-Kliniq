<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with(['user', 'poli'])->get();
        return response()->json($doctors);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'poli_id' => 'required|exists:poli,id',
            'nama' => 'required',
            'spesialisasi' => 'nullable',
            'no_str' => 'nullable',
            'alamat' => 'nullable',
            'no_telepon' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Create user first
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'dokter'
        ]);

        // Create doctor
        $doctor = Doctor::create([
            'user_id' => $user->id,
            'poli_id' => $request->poli_id,
            'nama' => $request->nama,
            'spesialisasi' => $request->spesialisasi,
            'no_str' => $request->no_str,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon
        ]);

        return response()->json($doctor->load(['user', 'poli']), 201);
    }

    public function show($id)
    {
        $doctor = Doctor::with(['user', 'poli'])->find($id);
        if (!$doctor) {
            return response()->json(['message' => 'Dokter tidak ditemukan'], 404);
        }
        return response()->json($doctor);
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['message' => 'Dokter tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required',
            'email' => 'sometimes|required|email|unique:users,email,' . $doctor->user_id,
            'poli_id' => 'sometimes|required|exists:poli,id',
            'nama' => 'sometimes|required',
            'spesialisasi' => 'nullable',
            'no_str' => 'nullable',
            'alamat' => 'nullable',
            'no_telepon' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Update user
        if ($request->has(['name', 'email'])) {
            $doctor->user->update($request->only(['name', 'email']));
        }

        // Update doctor
        $doctor->update($request->except(['name', 'email']));

        return response()->json($doctor->load(['user', 'poli']));
    }

    public function destroy($id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['message' => 'Dokter tidak ditemukan'], 404);
        }

        $doctor->user->delete(); // Delete associated user
        $doctor->delete();
        return response()->json(['message' => 'Dokter berhasil dihapus']);
    }
}
