<?php

namespace Domain\Recipe\DataTransferObjects;

class RecipeDTO
{
    public function __construct(
        public string $name,
        public string $instructions,
        public string $slug,
        public ?string $userPostId
    ) {}
}