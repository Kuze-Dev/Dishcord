<?php

namespace Domain\Comments\Actions;

use Domain\Comments\DataTransferObjects\CommentDTO;
use Domain\Comments\Models\Comment;

class CreateComment
{
    public function handle(CommentDTO $dto): Comment
    {
        return Comment::create([
            // Map DTO properties here
            'user_id' => $dto->userId,
            'user_post_id' => $dto->userPostId,
            'parent_id' => $dto->parentId, // Nullable for replies
            'body' => $dto->body,
        ]);
    }
}