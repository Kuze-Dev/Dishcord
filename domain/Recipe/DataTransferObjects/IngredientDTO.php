<?php

namespace Domain\Recipe\DataTransferObjects;

class IngredientDTO
{
    public function __construct(
        public ?int $id,
        public ?string $name,
        public ?string $type,
        public ?string $quantity,
        public ?string $unit,
        public bool $delete = false,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'] ?? null,
            type: $data['type'] ?? null,
            quantity: $data['quantity'] ?? null,
            unit: $data['unit'] ?? null,
            delete: $data['_delete'] ?? false,
        );
    }
}