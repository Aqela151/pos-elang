<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    // Menampilkan semua supplier
    public function index()
    {
        return Supplier::all();
    }

    // Menambahkan supplier baru
    public function store(Request $request)
    {
        $supplier = Supplier::create($request->all());

        return response()->json([
            'message' => 'Supplier berhasil ditambahkan',
            'data' => $supplier
        ], 201);
    }

    // Menampilkan satu supplier
    public function show(Supplier $supplier)
    {
        return $supplier;
    }

    // Mengupdate supplier
    public function update(Request $request, Supplier $supplier)
    {
        $supplier->update($request->all());

        return response()->json([
            'message' => 'Supplier berhasil diupdate',
            'data' => $supplier
        ]);
    }

    // Menghapus supplier
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return response()->json([
            'message' => 'Supplier berhasil dihapus'
        ]);
    }
}