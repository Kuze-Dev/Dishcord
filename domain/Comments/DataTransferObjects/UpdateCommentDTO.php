<?php

namespace Domain\Comments\DataTransferObjects;

class UpdateCommentDTO
{
    public function __construct(
        // Add your typed properties her
        public int $id,
        public int $userId,
        public int $userPostId,
        public ?int $parentId = null, // Nullable for replies
        public string $body,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            userId: $data['user_id'],
            userPostId: $data['user_post_id'],
            parentId: $data['parent_id'] ?? null, // Nullable for replies
            body: $data['body'],
        );
    }

}