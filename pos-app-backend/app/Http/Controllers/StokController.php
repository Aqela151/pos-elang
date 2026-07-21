<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $query = Stok::with(['produk','cabang']);

        if ($request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }

        return $query->get();
    }

    public function kasir(Request $request)
    {
        $query = Stok::with('produk');

        if ($request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }

        return $query->get()->map(fn (Stok $stok) => [
            'id' => $stok->id,
            'cabang_id' => $stok->cabang_id,
            'produk_id' => $stok->produk_id,
            'nama_produk' => $stok->produk->nama_produk ?? null,
            'kode_produk' => $stok->produk->kode_produk ?? null,
            'jumlah' => $stok->jumlah,
            'stok_minimum' => $stok->stok_minimum,
            'harga_beli' => $stok->produk->harga_beli ?? null,
            'harga_eceran' => $stok->produk->harga_eceran ?? null,
            'harga_grosir' => $stok->produk->harga_grosir ?? null,
        ]);
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