<?php

namespace App\Models;

use App\Models\User;
use Domain\Recipe\Models\Recipe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPost extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'image',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function recipe() :HasMany
    {
        return $this->hasMany(Recipe::class);
    }

}


