<?php

namespace Domain\Recipe\DataTransferObjects;

class UpdateRecipeData
{
    public function __construct(
        public string $name,
        public ?string $slug = null,
        public array $ingredients = [],
        public array $instructions,
    ) {}

    public static function fromArray(array $data): self
    {
        $ingredients = collect($data['ingredients'] ?? [])
            ->map(fn($item) => IngredientDTO::fromArray($item))
            ->all();

        return new self(
            name: $data['name'],
            slug: $data['slug'] ?? null,
            ingredients: $ingredients,
            instructions: $data['instructions'],

        );
    }
}
