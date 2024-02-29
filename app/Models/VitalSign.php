<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VitalSign extends Model
{
    use HasFactory;

    protected $fillable = [
        'heart_rate',
        'oxygen_rate',
        'jacket_id',
    ];

    public function jacket(): BelongsTo
    {
        return $this->belongsTo(Jacket::class);
    }
}
