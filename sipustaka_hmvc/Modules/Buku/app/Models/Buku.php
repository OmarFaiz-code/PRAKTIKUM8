<?php

namespace Modules\Buku\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    // Nama tabel ditulis eksplisit supaya tidak tertukar dengan aturan pluralisasi Laravel
    protected $table = 'buku';

    protected $fillable = ['judul', 'penulis', 'penerbit', 'tahun_terbit', 'stok'];
}
