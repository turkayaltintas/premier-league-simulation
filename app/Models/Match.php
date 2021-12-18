<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $casts = [
        'week_id' => 'int',
        'home' => 'int',
        'away' => 'int'
    ];

    protected $fillable = [
        'week_id',
        'home',
        'away'
    ];
}
