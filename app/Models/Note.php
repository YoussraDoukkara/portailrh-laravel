<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'date:d/m/Y',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
