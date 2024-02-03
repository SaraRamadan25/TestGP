<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'jacket_id',
        'content',
    ];

    public function jacket(): BelongsTo
    {
        return $this->belongsTo(Jacket::class);
    }
}
