<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
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
        // $value=$this->type;
        // $field=is_numeric($value)?'phone':'email';
        // $rule=($field=='email')?'|email':'';
        $rule=($this->type=='email')?'|email':'';
        return [
            //
            'type'=>'required|in:email,phone',
            'value' => 'required'.$rule,
        ];
    }
}
