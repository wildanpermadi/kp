<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PelegalisirRequest extends FormRequest
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
        $id = isset($this->pelegalisir) ? $this->pelegalisir->id : '';

        return [
            'nidn' => 'unique:pelegalisir,nidn,'.$id
        ];
    }

    public function messages()
    {
        return [
            'nidn.unique' => 'NIDN sudah digunakan'
        ];
    }
}
