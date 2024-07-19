<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
