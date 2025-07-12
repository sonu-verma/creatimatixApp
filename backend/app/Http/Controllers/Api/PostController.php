<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response as FacadesResponse;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
      public function index(Request $request)
    {

        $listType = $request->query('post_type', 'all'); 
        $posts = Post::with('user');
        // dd($posts[0]->post_owner);

        if(auth()->id() > 0 && $listType == 'posted_by_me'){
            $posts = $posts->where("posted_by", auth()->id());
        }
        $posts = $posts->orderBy('created_at', 'desc')->get();

//         dump($listType);
//         $query = str_replace(array('?'), array('\'%s\''), $posts->toSql());
// $query = vsprintf($query, $posts->getBindings());
// dd($query);

        return response()->json([
            'status' => true,
            'message' => 'Posts retrieved successfully.',
            'data' => $posts
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|nullable|string',
            'post_type'     => 'nullable|string|max:100',
            'activity_type' => 'nullable|string|max:100',
            'image'         => 'nullable|file|mimes:jpg,jpeg,png|max:20480',
            'tag_others'    => 'boolean',
            'location'      => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('post_images', $filename, 'public');
            $validated['media'] = $filename; 
        }

        $validated['posted_by'] = auth()->id(); // Or pass manually
        $validated['posted_on'] = now();
        $validated['modified_on'] = now();

        $post = Post::create($validated);

        return response()->json([
            'statusCode' => Response::HTTP_CREATED,
            'message' => 'Post created successfully!',
            'post'    => $post
        ], status: Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);
        return response()->json([
            'statusCode' => Response::HTTP_OK,
            'message' => 'post data',
            'data'    => $post
        ], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::find($id);

        if ($post && $post->posted_by !== auth()->id() ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

         return response()->json([
            'statusCode' => Response::HTTP_OK,
            'message' => 'post data',
            'data'    => $post
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $post = Post::find($id);

        if ($post && $post->posted_by !== auth()->id() ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|nullable|string',
            'post_type'     => 'nullable|string|max:100',
            'activity_type' => 'nullable|string|max:100',
            'image'         => 'nullable|file|mimes:jpg,jpeg,png|max:20480',
            'tag_others'    => 'boolean',
            'location'      => 'nullable|string|max:255',
        ]);


        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('post_images', $filename, 'public');
            $post->media = $filename; 
        }

        $post->title = $request->get('title');
        $post->description = $request->get('description');
        $post->posted_by = auth()->id();
        $post->modified_on = now();
        $post->save();

        return response()->json([
             'statusCode' => Response::HTTP_CREATED,
            'message' => 'Post updated successfully!',
            'post'    => $post->fresh()
        ], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
