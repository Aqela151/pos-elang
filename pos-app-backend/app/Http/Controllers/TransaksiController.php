<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with('detailTransaksi');

        if ($request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }

        return $query->orderBy('id', 'desc')->get();
    }

    public function histori(Request $request)
    {
        $query = Transaksi::with([
            'detailTransaksi.produk',
            'member',
            'cabang'
        ]);

        if ($request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }

        return $query
            ->orderBy('tanggal', 'desc')
            ->get();
    }

    public function store(Request $request)
    {
        // Validasi dasar: pastikan bayar tidak kurang dari total sebelum apa pun disimpan
        $total = (float) $request->total;
        $bayar = (float) $request->bayar;

        if ($bayar < $total) {
            return response()->json([
                'success' => false,
                'message' => 'Uang pembayaran kurang.'
            ], 422);
        }

        DB::beginTransaction();

        try {

            $transaksi = Transaksi::create([
                'kode_transaksi' => 'TRX-' . date('YmdHis'),
                'cabang_id'      => $request->cabang_id,
                'kasir_id'       => $request->kasir_id,
                'member_id'      => $request->member_id,
                'tanggal'        => now(),
                'total'          => $request->total,
                'metode_bayar'   => $request->metode_bayar,
                'bayar'          => $request->bayar,
                'kembali'        => $request->bayar - $request->total,
            ]);

            foreach ($request->items as $item) {

                $produk = Produk::find($item['produk_id']);

                if (!$produk) {
                    throw new \Exception("Produk tidak ditemukan.");
                }

                if ($produk->stok < $item['qty']) {
                    throw new \Exception("Stok {$produk->nama_produk} tidak mencukupi.");
                }

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id'    => $produk->id,
                    'qty'          => $item['qty'],
                    'harga'        => $item['harga'],
                    'subtotal'     => $item['qty'] * $item['harga'],
                ]);

                $produk->stok -= $item['qty'];
                $produk->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil',
                'data'    => $transaksi
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id)
    {
        return Transaksi::with('detailTransaksi')->findOrFail($id);
    }

    public function update(Request $request, int $id)
    {
        //
    }

    public function destroy(int $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        DB::transaction(function () use ($transaksi) {
            $transaksi->detailTransaksi()->delete();
            $transaksi->delete();
        });

        return response()->json([
            'message' => 'Transaksi berhasil dihapus'
        ]);
    }
}