<?php
namespace App\Http\Resources\Item;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $averageReview = $this->reviews()->avg('rate');
        $reviewsCount = $this->reviews()->count();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'price' => $this->price,
            'average_review' => $averageReview ?? 0,
            'reviews_count' => $reviewsCount,
            'quantity' => $this->quantity,
            'description' => $this->description,
        ];
    }
}
