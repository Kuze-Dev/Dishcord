<?php

namespace Domain\Recipe\Actions;

use Domain\Recipe\DataTransferObjects\IngredientDTO;
use Domain\Recipe\Models\Ingredients;

class UpdateIngridients
{
    public function handle(IngredientDTO $dto, int $id): Ingredients
    {
        $model = Ingredients::findOrFail($id);
        $model->update([
            'name' => $dto->name,
            'type' => $dto->type,
            'quantity' => $dto->quantity,
            'unit' => $dto->unit,
            'recipe_id' => $dto->recipeId,
        ]);
        return $model;
    }
}