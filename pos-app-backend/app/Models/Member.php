<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'member';

   protected $fillable = [
    'nama',
    'no_hp',
    'email',
    'alamat',
    'level',
    'total_transaksi',
    'cabang_id',
];
}