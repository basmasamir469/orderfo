<?php

namespace App\Http\Requests\sliders;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
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
            'text_en'=>'required',
            'text_ar'=>'required',
            'image'=>'required',
            'resturant_id'=>'required'
        ];
    }
}