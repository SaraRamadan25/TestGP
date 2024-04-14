<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayPalTransaction extends Model
{

    protected $fillable = [
        'transaction_id',
        'user_id',
        'payment_method',
        'amount',
        'currency',
        'status',
    ];

    // Define relationships if needed
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
