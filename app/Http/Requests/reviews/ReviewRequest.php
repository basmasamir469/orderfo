<?php

namespace App\Http\Requests\reviews;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'order_packaging'=>'between:1,5',

            'delivery_time'=>'between:1,5',

            'value_of_money'=>'between:1,5'
        ];
    }
}
