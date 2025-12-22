<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PoliController extends Controller
{
    public function index()
    {
        $polis = Poli::all();
        return response()->json($polis);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_poli' => 'required',
            'kode_poli' => 'required|unique:poli|max:10',
            'deskripsi' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $poli = Poli::create($request->all());
        return response()->json($poli, 201);
    }

    public function show($id)
    {
        $poli = Poli::find($id);
        if (!$poli) {
            return response()->json(['message' => 'Poli tidak ditemukan'], 404);
        }
        return response()->json($poli);
    }

    public function update(Request $request, $id)
    {
        $poli = Poli::find($id);
        if (!$poli) {
            return response()->json(['message' => 'Poli tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_poli' => 'required',
            'kode_poli' => 'required|max:10|unique:poli,kode_poli,' . $id,
            'deskripsi' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $poli->update($request->all());
        return response()->json($poli);
    }

    public function destroy($id)
    {
        $poli = Poli::find($id);
        if (!$poli) {
            return response()->json(['message' => 'Poli tidak ditemukan'], 404);
        }

        $poli->delete();
        return response()->json(['message' => 'Poli berhasil dihapus']);
    }
}
