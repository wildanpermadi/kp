<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermintaanLegalisirRequest extends FormRequest
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

        $id = isset($this->permintaan_legalisir) ? $this->permintaan_legalisir->id : '';

        return [
            //'no_ijazah' => 'unique:permintaan_legalisir,no_ijazah,'.$id,
            'file_ijazah' => 'mimes:pdf|max:5120'
        ];
    }

    public function messages()
    {
        return [
            //'no_ijazah.unique' => 'Nomor ijazah sudah digunakan',
            'file_ijazah.mimes' => 'Format file salah',
            'file_ijazah.max' => 'Ukuran file terlalu besar'
        ];
    }
}
