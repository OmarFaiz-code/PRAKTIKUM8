<?php

use Illuminate\Support\Facades\Route;
use Modules\Peminjaman\Http\Controllers\PeminjamanController;

// Admin maupun user boleh mengakses seluruh operasi peminjaman
Route::middleware(['auth', 'role:admin,user'])->group(function () {
    Route::get('peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    // 'data' harus didefinisikan SEBELUM {peminjaman}, kalau tidak "data" dianggap sebagai ID
    Route::get('peminjaman/data', [PeminjamanController::class, 'data'])->name('peminjaman.data');
    Route::post('peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::put('peminjaman/{peminjaman}', [PeminjamanController::class, 'update'])->name('peminjaman.update');
    Route::delete('peminjaman/{peminjaman}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
});
