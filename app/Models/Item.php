<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'item_id');
    }
}
