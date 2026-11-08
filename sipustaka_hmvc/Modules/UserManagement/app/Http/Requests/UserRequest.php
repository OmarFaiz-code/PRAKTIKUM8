<?php

namespace Modules\UserManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Saat edit, route punya parameter {user}; saat create tidak ada
        $user = $this->route('user');

        return [
            'name'     => ['required', 'string', 'max:100'],
            // unique tapi mengabaikan email milik user yang sedang diedit
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            // password wajib saat tambah, boleh kosong saat ubah
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:6'],
            'role'     => ['required', 'in:admin,user'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'email'    => 'Format email tidak valid.',
            'unique'   => ':attribute sudah digunakan.',
            'min'      => ':attribute minimal :min karakter.',
            'in'       => ':attribute tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama', 'email' => 'Email', 'password' => 'Password', 'role' => 'Role',
        ];
    }
}
