<?php

namespace App\Http\Requests;

use App\Rules\UniqueEmailAcrossTables;
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
            'username'=>'required|max:100',
            'email'=>['required', 'email', new UniqueEmailAcrossTables],
            'password'=>'required|min:8',
            'confirm_password' => 'required|same:password',
            'role_id'=>'required',
            'area_id'=>'required|exists:areas,id',
            'description'=>'nullable|max:255',
            'availability_times'=>'nullable|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'The username field is required.',
            'username.max' => 'The username may not be greater than 100 characters.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'confirm_password.required' => 'The password confirmation is required.',
            'confirm_password.same' => 'The password confirmation does not match.',
            'role_id.required' => 'The role ID field is required.',
            'area_id.required' => 'The area ID field is required.',
            'area_id.exists' => 'The selected area ID is invalid.',
            'description.max' => 'The description may not be greater than 255 characters.',
            'availability_times.max' => 'The availability times may not be greater than 255 characters.',
        ];
    }
}
