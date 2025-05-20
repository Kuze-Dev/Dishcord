<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInformation;
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
        $userInfo = UserInformation::class::where('user_id', $user->id)->get();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Fetched Sucessfullu',
            'data' => [
                'user' => $user,
                'userInfo' => $userInfo
            ]
        ]);
    }

    #[Get('users')]
    public function userPosts()
    {
        $users = User::all();
        return response()->json($users);
    }

}
