<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VitalSignResource extends JsonResource
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
            'heart_rate' => $this->heart_rate,
            'oxygen_rate' => $this->oxygen_rate,
            'jacket_id' => $this->jacket_id,
            ];
    }
}
