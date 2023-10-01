<?php

namespace App\Http\Requests\meals;

use Illuminate\Foundation\Http\FormRequest;

class StoreMealRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
            'name_en'=>'required',
            'name_ar'=>'required',
            'description_en'=>'required',
            'description_ar'=>'required',
            'image'=>'required',
            'type'=>'required',
            'price'=>'required',
            'offer_price'=>'nullable',
            'sizes'=>'required',
            'options'=>'required',
            'extras'=>'required',
            'resturant_id'=>'required'

        ];
    }
}
