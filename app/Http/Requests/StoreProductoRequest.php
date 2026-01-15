<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
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
            'nombre' => 'required|max:128|unique:productos,nombre',
            'precio_venta' => 'required|numeric',
            'costo' => 'required|numeric',
            'subcategoria_id' => 'required|exists:subcategorias,id'
            //'subcategoria_id' => 'exclude_if:categoria_id,null|required'
        ];
    }
}
