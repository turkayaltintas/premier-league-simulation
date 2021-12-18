<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    use HasFactory;
    protected $table = 'league';
    public $timestamps = false;

    protected $casts = [
        'team_id' => 'int',
        'points' => 'int',
        'played' => 'int',
        'won' => 'int',
        'lose' => 'int',
        'draw' => 'int',
        'goal_drawn' => 'int'
    ];

    protected $fillable = [
        'team_id',
        'points',
        'played',
        'won',
        'lose',
        'draw',
        'goal_drawn'
    ];
}
