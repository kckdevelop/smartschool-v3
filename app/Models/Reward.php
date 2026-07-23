<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $table = 'reward';
    protected $primaryKey = 'id_reward';
    public $timestamps = false;

    protected $fillable = [
        'detail_reward',
        'skor',
    ];

    protected $casts = [
        'skor' => 'integer',
    ];
}
