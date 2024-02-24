<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    

    // User Registration 
    public function register(request $request){

        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt( $fields['password']),

        ]);

        $token = $user->createToken('kalebtoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,

        ];
        return response($response, 201);
    }
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
    
        $user = User::where('email', $fields['email'])->first();
    
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json(['error' => 'Invalid email or password'], 401);
        }
    
        $token = $user->createToken('kalebtoken')->plainTextToken;
    
        $response = [
            'user' => $user,
            'token' => $token,
        ];
    
        return response()->json($response, 200);
    }
    /**public function logout(request $request){
        auth()->user()->tokens()->delete();

        return [
            'message'  => 'Logged out',
        ];
    }
    **/


    
}
