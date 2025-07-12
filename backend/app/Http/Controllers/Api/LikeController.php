<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Post;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function likeToggle($postId){
        $user = Auth::user();
        $post = Post::where('id', $postId)->get()->first();
        if($post){
            $action  = 'Post Likes.';
            if($post->likesByUser()->where('id_user', $user->id)->exists()){
                 $action  = 'Post dislikes.';
                $post->likesByUser()->detach($user->id);
            }else{
                $post->likesByUser()->attach($user->id);
            }

            return ResponseHelper::success("success", $post,$action, 201);
        }else{
            return ResponseHelper::error("error", "No Post Found", 404);
        }
    }
}
