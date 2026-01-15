<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePresentacioneRequest extends FormRequest
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
        $Presentacione = $this->route('presentacione');
        return [
            'nombre' => 'required|max:32|unique:presentaciones,nombre,' . $Presentacione->id,
            'clave' => 'nullable|max:16|unique:presentaciones,clave,' . $Presentacione->id
        ];
    }
}
