<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        return Kategori::all();
    }

    public function store(Request $request)
    {
        $kategori = Kategori::create($request->all());

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $kategori
        ], 201);
    }

    public function show(Kategori $kategori)
    {
        return $kategori;
    }

    public function update(Request $request, Kategori $kategori)
    {
        $kategori->update($request->all());

        return response()->json([
            'message' => 'Kategori berhasil diupdate',
            'data' => $kategori
        ]);
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return response()->json([
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}