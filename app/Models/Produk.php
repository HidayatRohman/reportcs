<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'harga',
        'kategori',
        'status',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];
}
