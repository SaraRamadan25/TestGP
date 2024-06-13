<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueEmailAcrossTables implements Rule
{
    public function passes($attribute, $value)
    {
        $tables = ['users', 'guards', 'trainers'];

        foreach ($tables as $table) {
            if (DB::table($table)->where('email', $value)->exists()) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'The :attribute has already been taken.';
    }
}
