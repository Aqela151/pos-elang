<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('produk')) {
            Schema::create('produk', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('kategori_id')->nullable();
                $table->unsignedBigInteger('supplier_id')->nullable();
                $table->string('kode_produk')->nullable();
                $table->string('nama_produk')->nullable();
                $table->decimal('harga_beli', 15, 2)->nullable();
                $table->decimal('harga_eceran', 15, 2)->nullable();
                $table->decimal('harga_grosir', 15, 2)->nullable();
                $table->integer('stok')->nullable();
                $table->string('gambar')->nullable();
            });

            return;
        }

        if (! Schema::hasColumn('produk', 'gambar')) {
            Schema::table('produk', function (Blueprint $table) {
                $table->string('gambar')->nullable()->after('stok');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('produk') && Schema::hasColumn('produk', 'gambar')) {
            Schema::table('produk', function (Blueprint $table) {
                $table->dropColumn('gambar');
            });
        }
    }
};
