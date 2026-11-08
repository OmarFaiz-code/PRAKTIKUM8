<?php

namespace Modules\Peminjaman\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PeminjamanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_peminjam'   => ['required', 'string', 'max:100'],
            'judul_buku'      => ['required', 'string', 'max:255'],
            'tanggal_pinjam'  => ['required', 'date'],
            'tanggal_kembali' => ['nullable', 'date', 'after_or_equal:tanggal_pinjam'],
            'status'          => ['required', 'in:Dipinjam,Dikembalikan'],
            // upload opsional, jpg/png/pdf, maksimal 2 MB (2048 KB)
            'bukti'           => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'required'              => ':attribute wajib diisi.',
            'date'                  => ':attribute harus berupa tanggal yang valid.',
            'in'                    => ':attribute tidak valid.',
            'max'                   => ':attribute maksimal :max (karakter/KB).',
            'mimes'                 => ':attribute harus berformat jpg, png, atau pdf.',
            'after_or_equal'        => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_peminjam'   => 'Nama peminjam',
            'judul_buku'      => 'Judul buku',
            'tanggal_pinjam'  => 'Tanggal pinjam',
            'tanggal_kembali' => 'Tanggal kembali',
            'status'          => 'Status',
            'bukti'           => 'Bukti peminjaman',
        ];
    }
}
