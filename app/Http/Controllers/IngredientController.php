<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Domain\Recipe\Actions\CreateIngredient;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Domain\Recipe\DataTransferObjects\IngredientDTO;

#[Prefix('api')]

class IngredientController extends Controller
{
    // Only Update

}
