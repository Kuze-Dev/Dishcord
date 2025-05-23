<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Domain\Recipe\Actions\CreateRecipe;
use Illuminate\Support\Facades\Validator;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Domain\Recipe\DataTransferObjects\RecipeDTO;
use Spatie\RouteAttributes\Attributes\Middleware;

#[Prefix('api')]
class RecipeController extends Controller
{
    #[Post('recipe', middleware:'auth:sanctum')]
    public function store(Request $request, CreateRecipe $action)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'instructions' => 'required',
            'slug' => 'required',
        ]);
    
        if ($validated->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validated->errors(),
            ], 422);
        }
    
        $user = Auth::user();
    
        $dto = new RecipeDTO(
            $request->name,
            $request->instructions,
            $request->slug,
            $user->id // Include user_id in DTO
        );
    
        $recipe = $action->create($dto);
    
        return response()->json([
            'success' => true,
            'recipe' => $recipe,
        ]);
    }
}