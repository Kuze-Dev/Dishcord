<?php

namespace Domain\Recipe\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ingridients extends Model
{
    protected $table = 'ingridients';

    protected $fillable = [
        'name',
        'type',
        'quantity',
        'unit',
        'recipe_id',
    ];

    public function recipe():BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
