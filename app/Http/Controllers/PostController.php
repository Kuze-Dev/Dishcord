<?php

namespace App\Http\Controllers;

use App\Models\UserPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Put;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;


#[Prefix('api')]
#[Middleware('auth:sanctum')]
class PostController extends Controller
{
    //
    #[
        Post('post')
    ]

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        $user->userPosts()->create([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $request->image,
        ]);

        $post = $user->userPosts()->get();

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'post' => $post
        ]);
    }

    #[
        Get('post')
    ]
    public function getPosts()
    {
       $post = UserPost::all();

        return response()->json([
            'success' => true,
             'data' => $post
        ]);
    }

    #[
        Put('post/{id}')
    ]
    public function updatePost(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[

            'title' => 'required',
            'body' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $user = Auth::user();
        $post = $user->userPosts()->find($id);

        $user->userPosts()->update([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $request->image,
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Post Updated Succcessfully',
            'post' => $post
        ]);

    }

    #[
        Delete('post/{id}')
    ]

    public function deletePost($id)
{
    $user = Auth::user();
    $post = $user->userPosts()->find($id);

    if (!$post) {
        return response()->json([
            'success' => false,
            'message' => 'Post not found'
        ], 404);
    }

    $post->delete();

    return response()->json([
        'success' => true,
        'message' => 'Post deleted successfully'
    ]);
}


}
