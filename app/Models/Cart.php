<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function promocode()
    {
        return $this->belongsTo(Promocode::class);
    }

    protected $casts = [
        'items' => 'array',
    ];
}

