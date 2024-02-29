<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Health extends Model
{
    use HasFactory;

    protected $table = 'health';

    protected $fillable = [
        'name',
        'age',
        'height',
        'weight',
        'heart_rate',
        'blood_type',
        'diseases',
        'allergies',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
