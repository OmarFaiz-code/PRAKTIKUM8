<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Buku\Models\Buku;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Minimal satu admin dan satu user
        User::updateOrCreate(
            ['email' => 'admin@sipustaka.test'],
            ['name' => 'Admin SiPustaka', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'user@sipustaka.test'],
            ['name' => 'User Biasa', 'password' => Hash::make('password'), 'role' => 'user']
        );

        // Data buku contoh (12 buku supaya pagination terlihat, 5 per halaman)
        if (Buku::count() === 0) {
            for ($i = 1; $i <= 12; $i++) {
                Buku::create([
                    'judul'        => "Buku Contoh $i",
                    'penulis'      => "Penulis $i",
                    'penerbit'     => "Penerbit " . (($i % 3) + 1),
                    'tahun_terbit' => 2010 + $i,
                    'stok'         => rand(1, 10),
                ]);
            }
        }
    }
}
