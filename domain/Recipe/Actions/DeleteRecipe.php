<?php

namespace Domain\Recipe\Actions;

use Domain\Recipe\DataTransferObjects\RecipeDTO;
use Domain\Recipe\Models\Recipe;

class DeleteRecipe
{
    public function handle(int $id): bool
    {
        $model = Recipe::findOrFail($id);
        $model->delete();
        return true;
    }
}