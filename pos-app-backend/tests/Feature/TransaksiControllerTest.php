<?php

namespace Tests\Feature;

use App\Models\DetailTransaksi;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TransaksiControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('detail_transaksi');
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('produk');

        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->integer('stok')->default(0);
            $table->timestamps();
        });

        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi');
            $table->integer('cabang_id')->nullable();
            $table->integer('kasir_id')->nullable();
            $table->integer('member_id')->nullable();
            $table->dateTime('tanggal')->nullable();
            $table->decimal('total', 12, 2)->default(0);
            $table->string('metode_bayar')->nullable();
            $table->decimal('bayar', 12, 2)->default(0);
            $table->decimal('kembali', 12, 2)->default(0);
        });

        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_id');
            $table->unsignedBigInteger('produk_id');
            $table->integer('qty')->default(0);
            $table->decimal('harga', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
        });
    }

    public function test_destroy_deletes_related_detail_transactions(): void
    {
        $produk = Produk::create([
            'nama_produk' => 'Test Produk',
            'stok' => 10,
        ]);

        $transaksi = Transaksi::create([
            'kode_transaksi' => 'TRX-TEST',
            'cabang_id' => 1,
            'kasir_id' => 1,
            'member_id' => null,
            'tanggal' => now(),
            'total' => 10000,
            'metode_bayar' => 'cash',
            'bayar' => 10000,
            'kembali' => 0,
        ]);

        DetailTransaksi::create([
            'transaksi_id' => $transaksi->id,
            'produk_id' => $produk->id,
            'qty' => 1,
            'harga' => 10000,
            'subtotal' => 10000,
        ]);

        $response = $this->deleteJson('/api/transaksi/' . $transaksi->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('transaksi', ['id' => $transaksi->id]);
        $this->assertDatabaseMissing('detail_transaksi', ['transaksi_id' => $transaksi->id]);
    }
}
