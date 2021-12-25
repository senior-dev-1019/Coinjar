<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinjarSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'userid',
        'seller_sound',
        'seller_sound_state',
        'buyer_sound',
        'buyer_sound_state',
        'selectedpair'
    ];
}
