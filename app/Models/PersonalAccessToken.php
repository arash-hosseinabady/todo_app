<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalAccessToken extends Model
{
    protected $casts = [
        'abilities' => 'json',
        'last_used_at' => 'timestamp',
        'expired_at' => 'timestamp'
    ];

    protected $fillable = [
        'name',
        'token',
        'abilities',
        'expires_at',
    ];
}
