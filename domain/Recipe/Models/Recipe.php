<?php

namespace Domain\Recipe\Models;

use App\Models\UserPost;
use Domain\Recipe\Models\Ingredients;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function ingredients():HasMany
    {
        return $this->hasMany(Ingredients::class);
    }
}
