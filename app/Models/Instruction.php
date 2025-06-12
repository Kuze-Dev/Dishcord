<?php

namespace App\Models;

use Domain\Recipe\Models\Recipe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Instruction extends Model
{
    protected $fillable = [
        'recipe_id',
        'step_description',
        'step_number',
    ];
    //

    public function recipe() :BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
