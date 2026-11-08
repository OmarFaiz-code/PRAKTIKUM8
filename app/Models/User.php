<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // 'role' wajib ada di $fillable agar bisa diisi lewat create()/update()
    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed', // password otomatis di-hash saat disimpan
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
