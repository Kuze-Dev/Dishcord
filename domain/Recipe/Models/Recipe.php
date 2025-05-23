<?php

namespace Domain\Recipe\Models;

use App\Models\UserPost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipe extends Model
{
    protected $fillable = [
        'name',
        'instructions',
        'slug',
        'user_post_id'
    ];

    public function userPost():BelongsTo
    {
        return $this->belongsTo(UserPost::class);
    }
}
