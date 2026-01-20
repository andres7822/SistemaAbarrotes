<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubcategoriaRequest extends FormRequest
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
        $Subcategoria = $this->route('subcategoria');
        return [
            'nombre' => 'required|max:64|unique:subcategorias,nombre,' . $Subcategoria->id,
            'nombre_categoria' => 'exclude_unless:categoria_id,-1|required|max:64|unique:categorias,nombre,' . $Subcategoria->categoria_id,
            'descuento' => 'exclude_unless:tipo_descuento_id,1,2,4,5|required',
            'piezas' => 'exclude_unless:tipo_descuento_id,3,4,5|required',
            'paga' => 'exclude_unless:tipo_descuento_id,3,5|required',
            'categoria_id' => 'required|integer',
            'tipo_descuento_id' => 'nullable'
        ];
    }
}
