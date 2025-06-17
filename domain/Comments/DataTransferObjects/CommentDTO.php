<?php

namespace Domain\Comments\DataTransferObjects;

class CommentDTO
{
    public function __construct(
        // Add your typed properties here
        public int $userId,
        public int $userPostId,
        public ?int $parentId = null, // Nullable for replies
        public string $body,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['user_id'],
            userPostId: $data['user_post_id'],
            parentId: $data['parent_id'] ?? null, // Nullable for replies
            body: $data['body'],
        );
    }
    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'user_post_id' => $this->userPostId,
            'parent_id' => $this->parentId,
            'body' => $this->body,
        ];
    }
}