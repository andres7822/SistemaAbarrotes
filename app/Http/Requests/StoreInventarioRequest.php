<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInventarioRequest extends FormRequest
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
            'existencia' => 'required|min:0',
            'observaciones' => 'nullable',
            'producto_id' => [
                'required',
                'exists:productos,id',
                Rule::unique('inventarios')->where(function ($query) {
                    return $query->where('bodega_id', $this->bodega_id);
                })
            ],
            'bodega_id' => 'required|exists:bodegas,id'
        ];
    }
}
