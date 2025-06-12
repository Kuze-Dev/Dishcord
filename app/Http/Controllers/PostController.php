<?php

namespace App\Http\Controllers;

use App\Models\UserPost;
use App\Models\Instruction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Domain\Recipe\Models\Recipe;
use Illuminate\Support\Facades\DB;
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
    #[Post('post')]
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Recipe validation
            'recipe.name' => 'required|string',
            'recipe.instructions' => 'required|string',
            'recipe.slug' => 'nullable|string',
            // Ingredients validation
            'recipe.ingredients' => 'nullable|array',
            'recipe.ingredients.*.name' => 'required|string',
            'recipe.ingredients.*.type' => 'nullable|string',
            'recipe.ingredients.*.quantity' => 'required|string',
            'recipe.ingredients.*.unit' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
    
        try {
            $user = Auth::user();
    
            // Store the image if exists
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('post_images', 'public');
            }
    
            // Create User Post
            $post = $user->userPosts()->create([
                'title' => $request->title,
                'body' => $request->body,
                'image' => $imagePath,
            ]);
    
            // Create Recipe linked to this post
            $recipeData = $request->input('recipe');
    
            $recipe = new Recipe([
                'name' => $recipeData['name'],
                'instructions' => $recipeData['instructions'],
                'slug' => $recipeData['slug'] ?? Str::slug($recipeData['name']),
            ]);
    
            $post->recipe()->save($recipe);
    
            // Add ingredients if provided
            if (!empty($recipeData['ingredients'])) {
                foreach ($recipeData['ingredients'] as $ingredientData) {
                    $recipe->ingredients()->create([
                        'name' => $ingredientData['name'],
                        'type' => $ingredientData['type'] ?? null,
                        'quantity' => $ingredientData['quantity'],
                        'unit' => $ingredientData['unit'],
                    ]);
                }
            }
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Post with recipe and ingredients created successfully.',
                'post' => $post->load('recipe.ingredients'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    #[Get('post')]
    public function getPosts()
    {
        $post = UserPost::all();

        return response()->json([
            'success' => true,
            'data' => $post
        ]);
    }

    #[Put('post/{id}')]
    public function updatePost(Request $request, $id)
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
            ]);
        }

        $user = Auth::user();
        $post = $user->userPosts()->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 404);
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('post_images', 'public');
            $post->image = $imagePath;
        }

        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Post Updated Successfully',
            'post' => $post
        ]);
    }

    #[Delete('post/{id}')]
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
