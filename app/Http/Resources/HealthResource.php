<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HealthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'age' => $this->age,
            'height' => $this->height,
            'weight' => $this->weight,
            'heart_rate' => $this->heart_rate,
            'blood_type' => $this->blood_type,
            'diseases' => $this->diseases,
            'allergies' => $this->allergies,
            'user_id' => $this->user_id,
        ];
    }
}
