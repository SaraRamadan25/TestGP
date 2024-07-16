<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sales',
        'new_arrivals',
        'delivery_status_changes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

