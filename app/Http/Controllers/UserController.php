<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;

#[Prefix('api'), Middleware('auth:sanctum')]
class UserController extends Controller
{
    #[Get('profile')]
    public function profile() :JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    #[Get('users')]
    public function getAllUser() :JsonResponse
    {
        $user = User::all();
        return response()->json($user);
    }

}
