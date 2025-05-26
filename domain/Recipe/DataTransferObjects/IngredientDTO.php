<?php

namespace Domain\Recipe\DataTransferObjects;

class IngredientDTO
{
    public function __construct(
        public string $name,
        public string $type,
        public string $quantity,
        public string $unit,
        public ?string $recipeId
    ) {}
}