<?php

namespace App\Http\Controllers;

use App\Models\UserPost;
use App\Models\Instruction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Domain\Recipe\Models\Recipe;
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
    #[Post('post')]
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ingredients' => 'required|array',
            'ingredients.*.name' => 'required|string',
            'ingredients.*.type' => 'required|string',
            'ingredients.*.quantity' => 'required|numeric',
            'ingredients.*.unit' => 'required|string',
            'instructions' => 'required|array',
            'instructions.*.step_description' => 'required|string',
            'instructions.*.step_number' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        // Upload image if provided
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        // Create the post
        $post = $user->userPosts()->create([
            'title' => $request->title,
            'body' => $request->body,
        ]);

            // Attach image using Spatie Media Library
        if ($request->hasFile('image')) {
         $post->addMediaFromRequest('image')->toMediaCollection('post_images', 's3');
        }

        // Create a recipe associated with this post
        $recipe = $post->recipes()->create([
            'name' => $request->title,
            'slug' => Str::slug($request->title),
        ]);

        // Create ingredients linked to the recipe
        foreach ($request->ingredients as $ingredient) {
            $recipe->ingredients()->create([
                'name' => $ingredient['name'],
                'type' => $ingredient['type'],
                'quantity' => $ingredient['quantity'],
                'unit' => $ingredient['unit'],
            ]);
        }

        // Create instructions linked to the user post
        foreach ($request->instructions as $instruction) {
          $recipe->instructions()->create([
                'step_description' => $instruction['step_description'],
                'step_number' => $instruction['step_number'],
            ]);

            // if (!$instructions){
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Failed to create instructions for the recipe'
            //     ], 500);
            // }
        }

        return response()->json([
            'success' => true,
            'message' => 'Post, recipe, ingredients, and instructions created successfully',
            'post' => $post->load('recipes.ingredients', 'recipes.instructions')->append('image_url'),
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
