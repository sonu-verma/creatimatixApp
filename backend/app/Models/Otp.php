<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = ['phone','otp_hash','attempts','expires_at'];
    protected $dates = ['expires_at'];
}
