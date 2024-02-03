<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Jacket extends Model
{
    use HasFactory;

    protected $fillable = [
        'modelno',
        'user_id',
        'batteryLevel',
        'start_rent_time',
        'end_rent_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function qrCode(): HasOne
    {
        return $this->hasOne(QrCode::class);
    }
}
