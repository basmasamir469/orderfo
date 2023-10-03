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
            'meal_type'=>'required|in:0,1',
            'sizes'=>'required|array',
            'sizes.*.name_en'=>'required',
            'sizes.*.name_ar'=>'required',
            'sizes.*.price'=>'required',
            'sizes.*.offer_price'=>'nullable',
            'options'=>'required|array',
            'options.*.name_en'=>'required',
            'options.*.name_ar'=>'required',
            'options.*.price'=>'nullable',
            'options.*.offer_price'=>'nullable',
            'extras'=>'required|array',
            'extras.*.name_en'=>'required',
            'extras.*.name_ar'=>'required',
            'extras.*.price'=>'required',
            'extras.*.offer_price'=>'nullable',
            'resturant_id'=>'required'

        ];
    }
}
