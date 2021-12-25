<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArbSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'audio_name',
        'percentage',
        'email_check',
        'avt',
    ];
}
