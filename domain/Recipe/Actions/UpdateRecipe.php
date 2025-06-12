<?php

namespace Domain\Recipe\Actions;

use App\Models\Instruction;
use Domain\Recipe\Models\Recipe;
use Domain\Recipe\Models\Ingredients;
use Domain\Recipe\DataTransferObjects\RecipeDTO;
use Domain\Recipe\DataTransferObjects\UpdateRecipeData;

class UpdateRecipe
{
    public function handle(UpdateRecipeData $data, Recipe $recipe): Recipe
    {
        $recipe->update([
            'name' => $data->name,
            'slug' => $data->slug,
        ]);





        foreach ($data->ingredients as $ingredientData) {
            if ($ingredientData->delete && $ingredientData->id) {
                Ingredients::where('id', $ingredientData->id)
                    ->where('recipe_id', $recipe->id)
                    ->delete();
                continue;
            }

            if ($ingredientData->id) {
                Ingredients::where('id', $ingredientData->id)
                    ->where('recipe_id', $recipe->id)
                    ->update([
                        'name' => $ingredientData->name,
                        'type' => $ingredientData->type,
                        'quantity' => $ingredientData->quantity,
                        'unit' => $ingredientData->unit,
                    ]);
            } else {
                $recipe->ingredients()->create([
                    'name' => $ingredientData->name,
                    'type' => $ingredientData->type,
                    'quantity' => $ingredientData->quantity,
                    'unit' => $ingredientData->unit,
                ]);
            }
        }

        // Update instructions
        $recipe->instructions()->delete(); // OR smarter sync logic if needed
        foreach ($data->instructions as $instructionData) {
            $recipe->instructions()->create([
                'step_number' => $instructionData['step_number'],
                'step_description' => $instructionData['step_description'],
            ]);
        }

        return $recipe->fresh(['ingredients','instructions']);
    }
}
