<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Domain\Recipe\Models\Recipe;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Domain\Recipe\Actions\CreateRecipe;
use Domain\Recipe\Actions\DeleteRecipe;
use Domain\Recipe\Actions\UpdateRecipe;
use Illuminate\Support\Facades\Validator;
use Spatie\RouteAttributes\Attributes\Put;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Prefix;
use Domain\Recipe\DataTransferObjects\RecipeDTO;
use Spatie\RouteAttributes\Attributes\Middleware;
use Domain\Recipe\DataTransferObjects\UpdateRecipeData;

#[Prefix('api')]
class RecipeController extends Controller
{
//Only update Recipe
#[Delete('recipe/{id}', middleware:'auth:sanctum')]    
    public function delete(int $id ,DeleteRecipe $action) {
        if(!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'you cant delete this recipe'
            ], 404);
        }
        $action->handle($id);
        return response()->json([
            'success' => true,
            'message' => 'Recipe deleted successfully'
        ]);
    }

#[Put('recipe/{id}', middleware:'auth:sanctum')]
    public function update(Request $request, int $id, UpdateRecipe $action)
        {
            $recipe = Recipe::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string',
                'instructions' => 'required|string',
                'slug' => 'nullable|string',
                'ingredients' => 'nullable|array',
                'ingredients.*.id' => [
                    'sometimes',
                    Rule::exists('ingredients', 'id')->where('recipe_id', $id),
                ],
                'ingredients.*.name' => 'nullable|string',
                'ingredients.*.type' => 'nullable|string',
                'ingredients.*.quantity' => 'nullable|string',
                'ingredients.*.unit' => 'nullable|string',
                'ingredients.*._delete' => 'sometimes|boolean',
            ]);
        
            $dto = UpdateRecipeData::fromArray($validated);
        
            $updatedRecipe = $action->handle($dto, $recipe);
        
            return response()->json([
                'success' => true,
                'recipe' => $updatedRecipe,
            ]);
    }
}