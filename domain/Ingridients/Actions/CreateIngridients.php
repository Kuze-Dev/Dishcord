<?php

namespace Domain\Ingridients\Actions;

use Domain\Ingridients\DataTransferObjects\IngridientsDTO;
use Domain\Ingridients\Models\Ingridients;

class CreateIngridients
{
    public function handle(IngridientsDTO $dto): Ingridients
    {
        return Ingridients::create([
            // Map DTO properties here, e.g. 'name' => $dto->name,
        ]);
    }
}