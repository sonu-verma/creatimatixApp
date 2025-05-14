<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurfImage extends Model
{
    use HasFactory;
    protected $fillable = ['id_turf', 'image_name','sort', 'is_default'];

    protected $appends = ['image_url'];

    public function turf()
    {
        return $this->belongsTo(Turf::class,'id', 'id_turf');
    }

    public function getImageUrlAttribute()
    {
        return asset('admin/uploads/turfs/').'/'.$this->image_name;
    }
}
