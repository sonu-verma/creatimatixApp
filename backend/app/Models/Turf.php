<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turf extends Model
{
    use HasFactory;
    protected $fillable = ['name','slug','location','address','timing','description','features','benefits','latitude','longitude','status','rules','pricing'];
    // protected $casts = ['rules' => 'array'];

    protected $appends = ['min_price'];

    public function sports()
    {
        return $this->hasMany(Sport::class, 'id_turf','id')->with('sportType');
    }

    public function images()
    {
        return $this->hasMany(TurfImage::class, 'id_turf','id');
    }

    public function getMinPriceAttribute(){
        return $this->sports()->min('rate_per_hour');
    }
}
