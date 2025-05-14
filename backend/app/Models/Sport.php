<?php

namespace App\Models;

use App\Models\Admin\SportType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    use HasFactory;
    protected $fillable = ['id_turf', 'id_sport', 'name', 'rate_per_hour', 'dimensions', 'capacity', 'rules', 'status'];
    // protected $casts = ['rules' => 'array'];

    public function turf()
    {
        return $this->belongsTo(Turf::class,'id', 'id_turf');
    }

    
    public function sportType()
    {
        return $this->belongsTo(SportType::class, 'id_sport', 'id');
    }
}

