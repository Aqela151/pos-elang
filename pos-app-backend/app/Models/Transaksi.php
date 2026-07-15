<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    // Karena tabel transaksi tidak memiliki created_at & updated_at
    public $timestamps = false;

    protected $fillable = [
        'kode_transaksi',
        'cabang_id',
        'kasir_id',
        'member_id',
        'tanggal',
        'total',
        'metode_bayar',
        'bayar',
        'kembali',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function kasir()
    {
        return $this->belongsTo(Kasir::class);
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}