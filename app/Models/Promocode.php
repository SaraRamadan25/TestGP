<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    use HasFactory;
    protected $casts = [
        'expires_at' => 'datetime',
    ];
    protected $table = 'promo_codes';

}
