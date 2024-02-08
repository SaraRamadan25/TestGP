<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'user_id',
    ];

    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
