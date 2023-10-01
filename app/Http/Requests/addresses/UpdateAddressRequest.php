<?php

namespace App\Http\Requests\addresses;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
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
            'name'=>'required',
            'street'=>'required',
            'latitude'=>'required',
            'longitude'=>'required',
            'building'=>'required',
            'area_id'=>'required',
            'additional_directions'=> 'nullable' 
        ];
    }
}
