<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{

    
    use HasFactory;
    
    protected $fillable = [
        'id_user',
        'name',
        'logo',
        'short_desc',
    ];

    public function getLogoAttribute($value)
    {
        return $value ? url('storage/team_images/' . $value) : null;
    }


    public function connection(){
        return $this->hasOne(TeamUserConnection::class, 'id_team', 'id')->where('id_user', auth()->id());
    }

    public function user(){
        return $this->belongsTo(User::class, 'id_user');
    }

}
