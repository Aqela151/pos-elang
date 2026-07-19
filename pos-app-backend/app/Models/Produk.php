<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';

    public $timestamps = false;

    protected $fillable = [
    'kategori_id',
    'supplier_id',
    'cabang_id',
    'kode_produk',
    'nama_produk',
    'harga_beli',
    'harga_eceran',
    'harga_grosir',
    'stok',
    'gambar',
    'is_active',
];
}