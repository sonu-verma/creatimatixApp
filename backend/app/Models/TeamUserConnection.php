<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamUserConnection extends Model
{
    protected $fillable = ['id_user', 'id_team'];


    protected $table = "team_user_connections";

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class,'id_team', 'id')->with('user');
    }
}
