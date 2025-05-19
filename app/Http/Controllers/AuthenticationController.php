<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;

#[Prefix('api')]
class AuthenticationController extends Controller
{

    #[ 
        Post('register')
    ]
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'address' => 'required',
            'phone_number' => 'required',
            'bio' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->firstname . ' ' . $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        UserInformation::create([
            'user_id' => $user->id,
            'first_name' => $request->firstname,
            'last_name' => $request->lastname,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'bio' => $request->bio,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully'
        ]);
        
    }

    #[
        Post('login')
    ]
    public function login (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ]);
        }

        $user = Auth::user();

        $token = $user->createToken('USER_TOKEN')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User Logged In Successfully',
            'token' => $token,
            'user' => $user
        ], 200);
    }
}
