<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected ?int $promo_code_id = null;

    protected $guarded = [];

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function promocode(): BelongsTo
    {
        return $this->belongsTo(Promocode::class);
    }

    protected $casts = [
        'items' => 'array',
        'promo_code_id' => 'integer',
    ];
}

