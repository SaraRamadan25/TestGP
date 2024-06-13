<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Trainer extends Model
{
    use HasFactory, HasApiTokens;
    protected $guarded = [];

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function getRouteKeyName(): string
    {
        return 'username';
    }
}
