<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            /** @var User */
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            return response()->json($success['token']);
        }
        return response()->json(['message' => 'Incorrect username or password.'], 401);
    }
}
