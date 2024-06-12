<?php

namespace App\Enums;

enum Role: int
{
    case PARENT = 1;
    case GUARD = 2;
    case TRAINER = 3;
}
