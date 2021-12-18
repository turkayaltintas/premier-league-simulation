<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $casts = [
        'season_id' => 'int'
    ];

    protected $fillable = [
        'name',
        'season_id'
    ];
}
