<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class JacketResource extends JsonResource
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
            'modelno' => $this->modelno,
            'batteryLevel' => $this->batteryLevel,
            'start_rent_time' => Carbon::parse($this->start_rent_time)->toDateTimeString(),
            'end_rent_time' => Carbon::parse($this->end_rent_time)->toDateTimeString(),
            'active'=>$this->active,
            'user_id' => $this->user_id->username,
            'area_id' => $this->area_id->name,
        ];
    }
}
