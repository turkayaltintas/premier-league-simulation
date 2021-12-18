<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamStrength extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $casts = [
        'team_id' => 'int',
        'is_home' => 'bool'
    ];

    protected $fillable = [
        'team_id',
        'is_home',
        'strength'
    ];
}
