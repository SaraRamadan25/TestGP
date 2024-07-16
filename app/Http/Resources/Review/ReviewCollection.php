<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReviewCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'data' => $this->collection->transform(function ($review) {
                return [
                    'id' => $review->id,
                    'rate' => $review->rate,
                    'price' => $review->item->price,
                    'item' => $review->item->name,
                    'review' => $review->review,
                    'date' => $review->created_at->format('d-m-Y'),
                ];
            }),
        ];
    }
}
