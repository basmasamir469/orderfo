<?php

namespace App\Http\Requests\resturants;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResturantRequest extends FormRequest
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
            'to_time'=>'required',
            'from_time'=>'required',
            'latitude'=>'required',
            'longitude'=>'required',
            'minimum_cost'=>'required',
            'delivery_fee'=>'required',
            'delivery_time'=>'required',
            'description'=>'nullable',
            'vat'=>'required',
            'category_id'=>'required',
            'address'=>'required',
            'logo'=>'nullable',
            'images'=>'nullable'
        ];
    }
}
