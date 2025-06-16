<?php

namespace App\Models;

use App\Models\User;
use Domain\Recipe\Models\Recipe;
use Spatie\MediaLibrary\HasMedia;
use Domain\Recipe\Models\Ingredients;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPost extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'user_id',
        'title',
        '_reviewed',
        '_published',
        'body',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function recipes() :HasOne
    {
        return $this->hasOne(Recipe::class);
    }

    public function getImageUrlAttribute()
{
    return $this->getFirstMediaUrl('post_images');
}

}


