<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardConfig extends Model
{
    protected $table = 'reward_config';

    protected $fillable = [
        'faculty_target',
        'other_target'
    ];
}
