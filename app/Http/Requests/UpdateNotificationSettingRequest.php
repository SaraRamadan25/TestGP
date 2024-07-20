<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationSettingRequest extends FormRequest
{
    public function authorize()
    {
        // Only allow authenticated users
        return true;
    }

    public function rules(): array
    {
        return [
            'sales' => 'boolean',
            'new_arrivals' => 'boolean',
            'delivery_status_changes' => 'boolean',
        ];
    }
}
