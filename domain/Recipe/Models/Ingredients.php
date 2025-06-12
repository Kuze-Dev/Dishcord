<?php

namespace Domain\Recipe\Models;

use App\Models\UserPost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ingredients extends Model
{
    protected $table = 'ingredients';

    protected $fillable = [
        'name',
        'type',
        'quantity',
        'unit',
    ];

    public function recipe():BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
