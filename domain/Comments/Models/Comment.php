<?php

namespace Domain\Comments\Models;

use App\Models\User;
use App\Models\UserPost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    //
    protected $fillable = [
        'user_id',
        'user_post_id',
        'parent_id', // Nullable for replies
        'body',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function userPost():BelongsTo
    {
        return $this->belongsTo(UserPost::class, 'user_post_id');
    }
}
