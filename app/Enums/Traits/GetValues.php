<?php

namespace App\Enums\Traits;

trait GetValues
{
    static function getValues()
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
