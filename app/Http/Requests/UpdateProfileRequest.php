<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'fname'=>'required',
            'lname'=>'required',
            'email'=>'required|email|unique:users,email,'.$this->user()->id,
            'password'=>'required',
            'phone' => 'required|unique:users,phone,'.$this->user()->id.'|regex:/(01)[0-9]{9}/',
            
        ];
    }
}
