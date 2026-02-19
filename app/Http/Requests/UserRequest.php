<?php

namespace App\Http\Requests;

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
        $userId = $this->route('user')?->id;

        return [
            'username' => [
                'required',
                'regex:/^[a-zA-Z0-9]+$/',
                Rule::unique('users', 'username')->ignore($userId),
            ],

            'nama' => 'required|string',

            'no_hp' => [
                'required',
                'numeric',
                Rule::unique('users', 'no_hp')->ignore($userId),
            ],

            'role' => 'required',

            'kandang_id' => 'nullable|exists:tb_kandang,id',

            'password' => [
                $userId ? 'nullable' : 'required',
                'min:6',
                'confirmed',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi',
            'username.unique'   => 'Username sudah digunakan',
            'username.regex'    => 'Username hanya boleh huruf dan angka',

            'nama.required'     => 'Nama wajib diisi',

            'no_hp.required'    => 'No HP wajib diisi',
            'no_hp.unique'      => 'No HP sudah digunakan user lain',
            'no_hp.numeric'     => 'No HP hanya boleh angka',

            'role.required'     => 'Role wajib dipilih',

            'password.required'  => 'Password wajib diisi',
            'password.min'       => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ];
    }
}
