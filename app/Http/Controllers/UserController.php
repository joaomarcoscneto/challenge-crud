<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(['users' => User::all()]);
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['password']  = Hash::make($data['password']);

        return response()->json(['user' => User::create($data)], 201);
    }
}
