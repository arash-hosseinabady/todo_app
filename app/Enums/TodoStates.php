<?php

namespace App\Enums;

use App\Enums\Traits\GetValues;

enum TodoStates: int
{
    use GetValues;

    case TODO = 0;
    case DOING = 1;
    case DONE = 2;
}
