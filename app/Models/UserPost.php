<?php

namespace App\Models;

use App\Models\User;
use Domain\Recipe\Models\Recipe;
use Spatie\MediaLibrary\HasMedia;
// use Domain\Recipe\Models\Ingredients;
use Domain\Comments\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
// use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_post_id');
    }

public function registerMediaCollections(): void
{
    $this->addMediaCollection('post_images')
        ->useDisk('s3') // Explicitly use the s3 (MinIO) disk
        ->singleFile();
}


   public function registerMediaConversions(Media $media = null): void
{
    $this->addMediaConversion('thumb')
         ->width(300)
         ->height(300)
         ->sharpen(10);

    $this->addMediaConversion('preview')
         ->width(800)
         ->height(600)
         ->sharpen(10);
}


}


