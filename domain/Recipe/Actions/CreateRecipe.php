<?php

namespace Domain\Recipe\Actions;

use Domain\Recipe\DataTransferObjects\RecipeDTO;
use Domain\Recipe\Models\Recipe;

class CreateRecipe
{
    public function create(RecipeDTO $dto): Recipe
    {
        return Recipe::create([
             'name' => $dto->name,
             'instructions' => $dto->instructions,
             'slug' => $dto->slug,
             'user_post_id' => $dto->userPostId
        ]);
    }
}