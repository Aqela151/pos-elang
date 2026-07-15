<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index()
    {
        return Stok::with(['produk','cabang'])->get();
    }

    public function store(Request $request)
    {
        $stok = Stok::create($request->all());

        return response()->json([
            'message' => 'Stok berhasil ditambahkan',
            'data' => $stok
        ],201);
    }

    public function show(Stok $stok)
    {
        return $stok->load(['produk','cabang']);
    }

    public function update(Request $request, Stok $stok)
    {
        $stok->update($request->all());

        return response()->json([
            'message' => 'Stok berhasil diupdate',
            'data' => $stok
        ]);
    }

    public function destroy(Stok $stok)
    {
        $stok->delete();

        return response()->json([
            'message' => 'Stok berhasil dihapus'
        ]);
    }
}