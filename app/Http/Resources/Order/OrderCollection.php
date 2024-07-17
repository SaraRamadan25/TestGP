<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($order) {
                return [
                    'id' => $order->id,
                    'status' => $order->status,
                    'total' => $order->total,
                    'order_number' => $order->order_number,
                    'quantity' => $order->quantity,
                    'date' => $order->created_at->format('d-m-Y'),
                ];
            }),
        ];


    }
}
