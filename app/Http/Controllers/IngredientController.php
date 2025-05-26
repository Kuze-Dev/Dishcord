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

class IngridientController extends Controller
{
    //
    #[Post('ingridient', middleware:'auth:sanctum')]
    public function store(Request $request, CreateIngredient $action)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'quantity' => 'required',
            'unit' => 'required',
            'recipe_id' => 'required',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validated->errors(),
            ], 422);
        }

        $user = Auth::user();

        $dto = new IngredientDTO(
            $request->name,
            $request->type,
            $request->quantity,
            $request->unit,
            $request->recipe_id, // Include recipe_id in DTO
            $user->id // Include user_id in DTO
        );

        $ingridient = $action->create($dto);

        return response()->json([
            'success' => true,
            'ingridient' => $ingridient,
        ]);
    }


}
