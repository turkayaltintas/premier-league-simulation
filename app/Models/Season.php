<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $casts = [
        'finished' => 'bool'
    ];

    protected $fillable = [
        'name',
        'finished'
    ];
}
