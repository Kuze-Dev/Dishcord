<?php

namespace Domain\Comments\Actions;

use Domain\Comments\Models\Comment;
use Domain\Comments\DataTransferObjects\UpdateCommentDTO;

class UpdateComment
{
    public function handle(UpdateCommentDTO $dto): Comment
    {
        $model = Comment::findOrFail($dto->id);
        $model->update([
            'user_id' => $dto->userId,
            'user_post_id' => $dto->userPostId,
            'parent_id' => $dto->parentId,
            'body' => $dto->body,
        ]);
        return $model;
    }
}