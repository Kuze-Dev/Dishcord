<?php

namespace Domain\Recipe\Actions;

use Domain\Recipe\DataTransferObjects\IngredientDTO;
use Domain\Recipe\Models\Ingredients;

class CreateIngredient
{
    public function create(IngredientDTO $dto): Ingredients
    {
        return Ingredients::create([
            'name' => $dto->name,
            'type' => $dto->type,
            'quantity' => $dto->quantity,
            'unit' => $dto->unit,
            'recipe_id' => $dto->recipeId
        ]);
    }
}