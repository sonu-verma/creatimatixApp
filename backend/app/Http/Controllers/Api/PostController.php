<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
      public function index()
    {
        $posts = Post::with('user');
        if(auth()->id() > 0){
            $posts = $posts->where("posted_by", auth()->id());
        }
        $posts = $posts->orderBy('created_at', 'desc')
                    ->get();
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
            'description'   => 'nullable|string',
            'post_type'     => 'nullable|string|max:100',
            'activity_type' => 'nullable|string|max:100',
            'image'         => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov|max:20480',
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
            'message' => 'Post created successfully!',
            'post'    => $post
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
