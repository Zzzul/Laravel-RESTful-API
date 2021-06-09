<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // ubah jadi true
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
            'category_id' => 'required|numeric',
            'photo' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
            'name' => 'required|string|min:4|max:191|unique:products,name',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|numeric|min:1',
            'exp_date'  => 'nullable|date|date_format:Y-m-d'
        ];
    }
}
