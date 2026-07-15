<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use Illuminate\Http\Request;

class DetailTransaksiController extends Controller
{
    public function index()
    {
        return DetailTransaksi::all();
    }

    public function store(Request $request)
    {
        $detail = DetailTransaksi::create($request->all());

        return response()->json([
            'message' => 'Detail transaksi berhasil ditambahkan',
            'data' => $detail
        ], 201);
    }

    public function show(DetailTransaksi $detailTransaksi)
    {
        return $detailTransaksi;
    }

    public function update(Request $request, DetailTransaksi $detailTransaksi)
    {
        $detailTransaksi->update($request->all());

        return response()->json([
            'message' => 'Detail transaksi berhasil diupdate',
            'data' => $detailTransaksi
        ]);
    }

    public function destroy(DetailTransaksi $detailTransaksi)
    {
        $detailTransaksi->delete();

        return response()->json([
            'message' => 'Detail transaksi berhasil dihapus'
        ]);
    }
}