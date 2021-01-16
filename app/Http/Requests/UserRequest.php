<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'min:8|confirmed',
            'foto' => 'mimes:jpg,jpeg,png|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'password.min' => 'Password kurang dari 8 karakter',
            'password.confirmed' => 'Password baru tidak sama',
            'foto.mimes' => 'Format foto salah',
            'foto.max' => 'Ukuran foto terlalu besar'
        ]; 
    }
}
