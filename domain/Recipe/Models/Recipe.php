<?php

namespace Domain\Recipe\Models;

use App\Models\UserPost;
use App\Models\Instruction;
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

      // Add cast to automatically handle JSON conversion


    public function userPost():BelongsTo
    {
        return $this->belongsTo(UserPost::class);
    }

    public function ingredients():HasMany
    {
        return $this->hasMany(Ingredients::class);
    }
    public function instructions():HasMany
    {
        return $this->hasMany(Instruction::class);
    }


}
