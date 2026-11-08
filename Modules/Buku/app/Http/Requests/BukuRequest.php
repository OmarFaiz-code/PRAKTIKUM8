<?php

namespace Modules\Buku\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Pembatasan akses sudah dilakukan middleware role:admin di route
        return true;
    }

    public function rules(): array
    {
        return [
            'judul'        => ['required', 'string', 'max:255'],
            'penulis'      => ['required', 'string', 'max:150'],
            'penerbit'     => ['required', 'string', 'max:150'],
            'tahun_terbit' => ['required', 'integer', 'between:1900,' . date('Y')],
            'stok'         => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'integer'  => ':attribute harus berupa angka.',
            'max'      => ':attribute maksimal :max karakter.',
            'min'      => ':attribute minimal :min.',
            'between'  => ':attribute harus antara :min sampai :max.',
        ];
    }

    public function attributes(): array
    {
        return [
            'judul'        => 'Judul',
            'penulis'      => 'Penulis',
            'penerbit'     => 'Penerbit',
            'tahun_terbit' => 'Tahun terbit',
            'stok'         => 'Stok',
        ];
    }
}
