<?php

namespace Domain\Recipe\Actions;

use Domain\Recipe\DataTransferObjects\IngridientDTO;
use Domain\Recipe\Models\Ingridients;

class CreateIngridient
{
    public function create(IngridientDTO $dto): Ingridients
    {
        return Ingridients::create([
            'name' => $dto->name,
            'type' => $dto->type,
            'quantity' => $dto->quantity,
            'unit' => $dto->unit,
            'recipe_id' => $dto->recipeId
        ]);
    }
}