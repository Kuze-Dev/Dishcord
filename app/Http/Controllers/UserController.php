<?php

namespace App\Http\Controllers;

use LDAP\Result;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserInformation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Put;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;

#[Prefix('api/user'), Middleware('auth:sanctum')]
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
    #[Put('edit-profile', middleware: 'auth:sanctum')]
    public function editProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'firstname' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'bio' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        $user = Auth::user();

        $validatedData = $validator->validated();

        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
        ]);

        $user->userInformation()->update([
            'first_name' => $validatedData['firstname'] ?? '',
            'last_name' => $validatedData['lastname'] ?? '',
            'phone_number' => $validatedData['phone_number'],
            'address' => $validatedData['address'],
            'bio' => $validatedData['bio'] ?? '',
        ]);

        $userInfo = $user->userInformation()->get();

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => $user,
                'userInfo' => $userInfo
            ]
        ]);
    }
}
