<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'email', 'phone', 'password', 'role', 'api_token','profile', 'short_desc'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Accessor to get the full path of the profile image.
     *
     * @return string|null
     */
    public function getProfileAttribute($value)
    {
        return $value ? url('storage/profile_images/' . $value) : null;
    }


    public function isAdmin(){
       return $this->role === 'admin';
    }

    public function isManager(){
        return $this->role === 'manager';
    }

    public function isUser(){
        return $this->role === 'user';
    }


    public function teams(){
        return $this->hasMany(Team::class, 'id_user');
    }

    public function acceptedConnections()
    {
        return $this->hasMany(TeamUserConnection::class, 'id_user', 'id')
            ->where('status', 'accepted');
    }

    public function pendingOrRejectedRequests()
    {
        return $this->hasMany(TeamUserConnection::class, 'id_user', 'id')->with('teams')
            ->whereIn('status', ['pending', 'rejected']);
    }

    public function likesByPost(){
        return $this->belongsToMany(User::class, 'likes', 'id_user','id_post')->withTimestamps();
    }
}


