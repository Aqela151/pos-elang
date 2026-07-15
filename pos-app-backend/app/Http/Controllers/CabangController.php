<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    // READ
    public function index()
    {
        return Cabang::all();
    }

    // CREATE
    public function store(Request $request)
    {
        return Cabang::create($request->all());
    }

    // READ SATU DATA
    public function show(Cabang $cabang)
    {
        return $cabang;
    }

    // UPDATE
    public function update(Request $request, Cabang $cabang)
    {
        $cabang->update($request->all());

        return $cabang;
    }

    // DELETE
    public function destroy(Cabang $cabang)
    {
        $cabang->delete();

        return response()->json([
            "message" => "Cabang berhasil dihapus"
        ]);
    }
}