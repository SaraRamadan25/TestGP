<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends Model
{
    protected $fillable = ['appointment','user_id'];

    use HasFactory;

    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
