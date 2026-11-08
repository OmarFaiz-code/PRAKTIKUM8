<?php

namespace Modules\Peminjaman\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'nama_peminjam', 'judul_buku', 'tanggal_pinjam',
        'tanggal_kembali', 'status', 'bukti',
    ];

    // Format tanggal Y-m-d supaya cocok dengan <input type="date"> saat edit
    protected $casts = [
        'tanggal_pinjam'  => 'date:Y-m-d',
        'tanggal_kembali' => 'date:Y-m-d',
    ];

    // Atribut tambahan 'bukti_url' ikut muncul di JSON
    protected $appends = ['bukti_url'];

    public function getBuktiUrlAttribute(): ?string
    {
        return $this->bukti ? Storage::disk('public')->url($this->bukti) : null;
    }
}
