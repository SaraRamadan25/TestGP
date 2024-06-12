<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Guard extends Model
{
    use HasFactory, HasApiTokens;
    protected $guarded = [];

    public function areas(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function jackets(): HasMany
    {
        return $this->hasMany(Jacket::class);
    }
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
