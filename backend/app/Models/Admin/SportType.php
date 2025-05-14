<?php

namespace App\Models\Admin;

use App\Models\Sport;
use Illuminate\Database\Eloquent\Model;

class SportType extends Model
{
    protected $table = 'sport_types';

    protected $fillable = ['name', 'status'];



    public function sports(){
        return $this->hasMany(Sport::class, 'id_sport', 'id');
    }
}
