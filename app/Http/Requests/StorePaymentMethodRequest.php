<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentMethodRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'card_holder_name' => 'required|string|max:255',
            'card_number' => 'required|string|digits_between:16,19',
            'cvv' => 'required|string|digits:3',
            'expiration_date' => 'required|string|date_format:m/y',
            'default' => 'boolean',
        ];
    }
}
