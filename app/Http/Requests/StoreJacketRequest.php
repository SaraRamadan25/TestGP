<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJacketRequest extends FormRequest
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
            'modelno' => 'required|string',
            'batteryLevel' => 'required|integer',
            'start_rent_time' => 'required|date',
            'end_rent_time' => 'required|date',
            'user_id' => 'required|integer',
            'area_id' => 'required|integer',
        ];
    }
}
