<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Guard = 'guard';

    case Parent = 'parent';
    case Trainer = 'trainer';

  public static function getRole(string $role): string
  {
    return match ($role) {
      'admin' => 'Admin',
      'guard' => 'Guard',
      'parent' => 'Parent',
        'trainer' => 'Trainer',
    };
  }
}
