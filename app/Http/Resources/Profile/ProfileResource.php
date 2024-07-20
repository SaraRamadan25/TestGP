<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'email' => $this->email,
            'avatar' => $this->avatar,
            'orders_count' => $this->orders()->count(),
            'shipping_addresses' => $this->shippingAddresses,
            'payment_methods_count' => $this->checkouts()->distinct('payment_card_number')->count(),
            'reviews_count' => $this->reviews()->count(),
            'settings' => [
                'notifications' => $this->notificationSetting->notifications ?? null,
                'password' => '********',
                'faq' => 'Frequently Asked Questions link or content',
                'contact' => 'Contact link or content'
            ]
        ];
    }
}
