<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
   public function register(Request $request){
    // Validate request
    $Validated = $request->validate([
        'name' => 'required|max:100',
        'email' => 'required|unique:users|max:100',
        'password' => 'required',
        "phone" => 'required',
        "roles" => 'required',
    ]);

    //password encryption
    $Validated['password'] =Hash::make($Validated['password']);

    $user = User::create($Validated);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'user' => $user
    ], 201);
   }

   public function logout(Request $request){
    $request->user()->currentAccessToken()->delete();
    return response()->json([
        'message' => 'Logout success',
    ], 200);
   }

   public function login(Request $request){

    // Validate the request
    $validated = $request->validate([
        'email' => 'required',
        'password' => 'required'
    ]);

    $user = User::where('email', $validated['email'])->first();

    // if(!$user || !Hash::check($validated['password'], $user->password)){
    //     return response()->json([
    //         'message' => 'Bad credential',
    //     ], 401);
    // }

    if(!$user){
        return response()-> json([
            'message' => 'User not found'
        ], 401);
    }
    if(!Hash::check($validated['password'], $user->password)){
        return response()->json([
            'message' => 'invalid password'
        ], 401);
    }


    $token =$user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'user' => $user,
    ], 200);

   }
}
