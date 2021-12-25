<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sound extends Model
{
    use HasFactory;
    protected $fillable = [
        'userId',
        'sound1',
        'sound2',
        'sound3',
        'sound4',
        'sound5',
        'sound1_check',
        'sound2_check',
        'sound3_check',
        'sound4_check',
        'sound5_check',
    ];

}
