<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // user login boleh update profil sendiri
    }

    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'regex:/^[a-zA-Z0-9]+$/',
                Rule::unique('users', 'username')->ignore($this->user()->id),
            ],
            'nama' => 'required|string',
            'no_hp' => [
                'required',
                'numeric',
                Rule::unique('users', 'no_hp')->ignore($this->user()->id),
            ],
            'password' => 'nullable|min:6|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi',
            'username.unique'   => 'Username sudah digunakan',
            'username.regex'      => 'Username tidak boleh mengandung spasi atau simbol',
            'nama.required'     => 'Nama wajib diisi',
            'no_hp.required'    => 'No HP wajib diisi',
            'no_hp.unique'        => 'No HP sudah digunakan user lain',
            'no_hp.numeric'       => 'No HP hanya boleh angka',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min'      => 'Password minimal 6 karakter',
        ];
    }
}
