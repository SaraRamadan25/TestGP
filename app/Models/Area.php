<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Area extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    protected function jackets(): HasMany
    {
        return $this->hasMany(Jacket::class);
    }

    protected function climate(): HasOne
    {
        return $this->hasOne(Climate::class);
    }
}
