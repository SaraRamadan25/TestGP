<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'=>'required|max:100',
            'email' => 'required', 'email', 'unique:users,email',
            'password'=>'required|min:8',
            'confirm_password' => 'required|same:password',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.max' => 'The name may not be greater than 100 characters.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'confirm_password.required' => 'The password confirmation is required.',
            'confirm_password.same' => 'The password confirmation does not match.',
        ];
    }
}
