<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Climate extends Model
{
    use HasFactory;

    protected $fillable = [
        'tide',
        'sea_level',
        'wind',
        'temperature',
        'day_name',
        'day_date',
        'area_id',
    ];

    protected function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }
}
