<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
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
            'nombre' => 'required|max:256|unique:clientes,nombre',
            'sexo' => 'nullable|exists:sexos,id',
            'domicilio' => 'nullable',
            'telefono_celular' => 'nullable|digits_between:0,10',
            'correo_electronico' => 'nullable|max:256|email',
            'fecha_nacimiento' => 'nullable'
        ];
    }
}
