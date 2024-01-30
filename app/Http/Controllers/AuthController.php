<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $token = $request->user()->createToken('auth-token')->plainTextToken;
            $user =Auth::user();
            return response()->json([
                'token' => $token,
                'user'  => $user
        ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            // Revoke the user's tokens
            $request->user()->tokens()->delete();

            return response()->json(['message' => 'Logged out']);
        }

        // If the user is not authenticated, return an error response
        return response()->json(['message' => 'User not authenticated'], 401);


        // $request->user()->tokens()->delete();

        // return response()->json(['message' => 'Logged out']);
    }

}



