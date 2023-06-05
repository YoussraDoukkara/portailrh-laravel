<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use HasFactory;

    protected $casts = [
        'is_breakable' => 'boolean',
        'is_leave' => 'boolean',
        'is_rest' => 'boolean',
    ];
}
