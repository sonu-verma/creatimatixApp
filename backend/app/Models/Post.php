<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $appends = ['post_owner', 'cheer_count', 'is_cheer'];
     protected $fillable = [
        'title',
        'description',
        'post_type',
        'activity_type',
        'media',
        'posted_by',
        'posted_on',
        'modified_on',
        'tag_others',
        'location',
    ];

    protected $casts = [
        'posted_on' => 'datetime',
        'modified_on' => 'datetime',
        'tag_others' => 'boolean',
    ];


    


    public function user(){
        return $this->belongsTo(User::class, 'posted_by', 'id');
    }

    public function getPostedOnAttribute($value){
        return date("y-m-d h:i", strtotime($value));
    }

    public function getMediaAttribute($value){
        // return $value ? url('storage/post_images/' . $value) : null;
       return $value?asset('/storage/post_images/'.$value): '';
    }

    public function getPostOwnerAttribute(){
        return auth()->id() == $this->posted_by;
    }

    public function getCheerCountAttribute(){
        return $this->likesByUser()->count();
    }

    public function getIsCheerAttribute(){
        return $this->likesByUser()->wherePivot('id_user', auth()->id())->exists();
    }

    public function likesByUserCount(){
        return $this->belongsToMany(POST::class, 'likes', 'id_post','id')->withTimestamps();
    }

       public function likesByUser(){
        return $this->belongsToMany(POST::class, 'likes', 'id_post','id_user')->withTimestamps();
    }
}
