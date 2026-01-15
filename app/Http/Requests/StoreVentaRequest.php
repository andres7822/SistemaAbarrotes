<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVentaRequest extends FormRequest
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
            //'tipo_venta_id' => 'required|exists:tipo_ventas,id',
            'cliente_id' => 'required|exists:clientes,id',
            //'user_id' => 'required|exists:users,id',
            'inventarios.*' => 'nullable',
            'nombres.*' => 'nullable',
            'bodegas.*' => 'nullable',
            'precios.*' => 'nullable',
            'cantidades.*' => 'nullable',
            'totales.*' => 'nullable',
            'venta_detalle_id.*' => 'nullable'
        ];
    }
}
