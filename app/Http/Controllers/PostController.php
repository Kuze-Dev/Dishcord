<?php

namespace App\Http\Controllers;

use App\Models\UserPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;

#[Prefix('api/post')]
#[Middleware('auth:sanctum')]
class PostController extends Controller
{
    //
    #[
        Post('create')
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
        Get('read')
    ]
    public function getPosts()
    {
       $post = UserPost::all();

        return response()->json([
            'success' => true,
            $post 
        ]);
    }


}
