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
            'username'=>'required|max:100',
            'email'=>'required|email',
            'password'=>'required|min:8',
            'confirm_password'=>'required|same:password',
        ];
    }
}
