# SiPustaka (Laravel + nwidart/laravel-modules, HMVC)

Isi zip ini adalah file-file yang ditimpa/ditambahkan ke project Laravel baru.
Struktur folder sudah sama dengan project Laravel, jadi cukup di-extract ke root project.

## Langkah setup

```bash
# 1. Project baru
composer create-project laravel/laravel sipustaka_hmvc
cd sipustaka_hmvc

# 2. Database: buat db_sipustaka di phpMyAdmin, lalu edit .env
#    DB_CONNECTION=mysql
#    DB_HOST=127.0.0.1
#    DB_PORT=3306
#    DB_DATABASE=db_sipustaka
#    DB_USERNAME=root
#    DB_PASSWORD=

# 3. Modul HMVC + merge plugin
composer require nwidart/laravel-modules
composer require wikimedia/composer-merge-plugin
```

Edit `composer.json` (tambahkan di dalam `extra`, dan izinkan plugin di `config`):

```json
"extra": {
    "laravel": { "dont-discover": [] },
    "merge-plugin": {
        "include": ["Modules/*/composer.json"]
    }
},
"config": {
    "allow-plugins": {
        "wikimedia/composer-merge-plugin": true
    }
}
```

Lalu:

```bash
composer dump-autoload

# 4. Generate kerangka modul
php artisan module:make Buku Peminjaman UserManagement

# 5. Extract isi zip ini ke root project (timpa file yang ada)

# 6. Migrasi, seeder, dan storage link (untuk upload bukti)
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan serve
```

Akun seeder:
- Admin: `admin@sipustaka.test` / `password`
- User : `user@sipustaka.test` / `password`

## Cara menunjukkan bukti (dari soal)
- Error 403: login sebagai user, buka `/admin/users`.
- Network tab: buka DevTools (F12) > Network > Fetch/XHR, lalu tambah/ubah/hapus di halaman `/peminjaman`.
- Folder upload: cek `storage/app/public/bukti`.
- Kolom path: phpMyAdmin > db_sipustaka > tabel `peminjaman` > kolom `bukti`.
