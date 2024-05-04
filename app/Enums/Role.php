<?php

namespace App\Enums;

use InvalidArgumentException;

class Role
{
    public const ADMIN = 'admin';
    public const GUARD = 'guard';
    public const PARENT = 'parent';
    public const TRAINER = 'trainer';

    public static function getRole(string $role): string
    {
        return match ($role) {
            self::ADMIN, self::GUARD, self::PARENT, self::TRAINER => $role,
            default => throw new InvalidArgumentException("Invalid role: $role"),
        };
    }
}
