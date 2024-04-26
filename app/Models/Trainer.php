<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trainer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jackets(): HasMany
    {
        return $this->hasMany(Jacket::class);

    }
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }
}
