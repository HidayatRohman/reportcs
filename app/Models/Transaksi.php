<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nama',
        'no_tlp',
        'produk_id',
        'chat',
        'provinsi',
        'kota',
        'transaksi',
        'keterangan',
        'komentar',
    ];

    protected $casts = [
        'transaksi' => 'decimal:2',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
