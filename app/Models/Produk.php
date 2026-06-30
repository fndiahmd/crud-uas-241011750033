<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = ['gambar', 'nama_produk', 'kategori_id', 'harga', 'deskripsi'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
