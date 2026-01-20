<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTiendaRequest extends FormRequest
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
        $Tienda = $this->route('tienda');
        return [
            'nombre' => 'required|max:64|unique:tiendas,nombre,' . $Tienda->id,
            'domicilio' => 'nullable',
            'descripcion' => 'nullable',
            'imagen' => 'nullable',
            'encabezado_ticket' => 'nullable',
            'pie_ticket' => 'nullable',
        ];
    }
}
