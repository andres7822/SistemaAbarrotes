<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
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
        $Producto = $this->route('producto');
        return [
            'nombre' => 'required|max:128|unique:productos,nombre,' . $Producto->id,
            'precio_venta' => 'required|numeric',
            'costo' => 'required|numeric',
            'subcategoria_id' => 'required|exists:subcategorias,id'
        ];
    }
}
