<?php

namespace Domain\Recipe\DataTransferObjects;

class UpdateRecipeData
{
    /** @param IngredientData[] $ingredients */
    public function __construct(
        public string $name,
        public string $instructions,
        public ?string $slug = null,
        public array $ingredients = [],
    ) {}

    public static function fromArray(array $data): self
    {
        $ingredients = collect($data['ingredients'] ?? [])
            ->map(fn($item) => IngredientDTO::fromArray($item))
            ->all();

        return new self(
            name: $data['name'],
            instructions: $data['instructions'],
            slug: $data['slug'] ?? null,
            ingredients: $ingredients,
        );
    }
}