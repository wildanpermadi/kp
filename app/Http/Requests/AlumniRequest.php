<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlumniRequest extends FormRequest
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
        $alumni_id = '';

        if(isset($this->alumni)) {
            $alumni_id = $this->alumni->id;
        } elseif(auth()->user()->role == 'alumni') {
            $alumni_id = auth()->user()->alumni->id;
        }

        return [
            'nim' => 'unique:alumni,nim,'.$alumni_id,
            'email' => 'unique:alumni,email,'.$alumni_id,
            'sk_kelulusan' => 'unique:alumni,sk_kelulusan,'.$alumni_id
        ];
    }

    public function messages()
    {
        return [
            'nim.unique' => 'NIM sudah digunakan',
            'email.unique' => 'Email sudah digunakan',
            'sk_kelulusan.unique' => 'SK Kelulusan sudah digunakan'
        ];
    }
}
