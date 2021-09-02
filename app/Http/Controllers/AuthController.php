<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //register user
    public function register(Request $request){
        //validate field
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);
        //create user
        // $user = User::create([
        //     'name'=> $credentials['name'],
        //     'email' => $credentials['email'],
        //     'password' => bcrypt($credentials['password']),
        // ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        // return user & token in response
        // return response([
        //     'users' => $user,
        //     'token' => $user->createToken('secret')->plainTextToken
        // ],200);

        $user->save();

        return response()->json([
            'message' => 'User has been registered'],200
        );
    }

    public function login(Request $request)
    {
        //validate field
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials)){
            return response()->json(['message' => 'Unauthorized'],401);
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response(
            [
                'message' => 'Logout Success'
            ],200
        );
    }

    //sample
}
