<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Trainer extends Model
{
    use HasFactory, HasApiTokens;
    protected $guarded = [];

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
