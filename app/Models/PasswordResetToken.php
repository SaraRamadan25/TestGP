<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    use HasFactory;
    protected $primaryKey = 'email';

    protected $guarded = [];
    const UPDATED_AT = null;
}
