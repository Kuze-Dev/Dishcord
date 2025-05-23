<?php

namespace Domain\Recipe\Actions;

use Domain\Recipe\DataTransferObjects\RecipeDTO;
use Domain\Recipe\Models\Recipe;

class UpdateRecipe
{
    public function handle(RecipeDTO $dto, int $id): Recipe
    {
        $model = Recipe::findOrFail($id);
        $model->update([
            
                ]);
        return $model;
    }
}