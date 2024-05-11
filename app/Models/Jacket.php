<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'guard_id',
    ];

    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function qrCode(): HasOne
    {
        return $this->hasOne(QrCode::class);
    }

    public function location(): HasOne
    {
        return $this->hasOne(Location::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function guardUser(): BelongsTo
    {
        return $this->belongsTo(Guard::class);
    }
}
