<?php

use Illuminate\Support\Facades\Route;
use Modules\UserManagement\Http\Controllers\UserController;

// Seluruh halaman ini khusus admin. User biasa akan mendapat halaman 403.
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::resource('users', UserController::class)->except('show');
});
