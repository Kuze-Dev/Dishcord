<?php

namespace Domain\Recipe\Actions;

use Domain\Recipe\DataTransferObjects\RecipeDTO;
use Domain\Recipe\Models\Recipe;

class DeleteRecipe
{
    public function handle(int $id): bool
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->delete();
        return true;
    }
}