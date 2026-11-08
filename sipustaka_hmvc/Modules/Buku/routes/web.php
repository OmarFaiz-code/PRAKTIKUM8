<?php

use Illuminate\Support\Facades\Route;
use Modules\Buku\Http\Controllers\BukuController;

Route::middleware('auth')->group(function () {
    // Admin dan user boleh melihat daftar buku
    Route::get('buku', [BukuController::class, 'index'])->name('buku.index');

    // Hanya admin yang boleh tambah, ubah, hapus
    Route::middleware('role:admin')->group(function () {
        Route::resource('buku', BukuController::class)->except(['index', 'show']);
    });
});
