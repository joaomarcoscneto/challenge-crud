<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendUserMailRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Services\SendUserMailService;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(['users' => User::all()]);
    }

    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();
        $data['password']  = Hash::make($data['password']);

        return response()->json(['user' => User::create($data)], 201);
    }

    public function show(User $user)
    {
        return response()->json(['user' => $user]);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $data = $request->validated();

        if (key_exists('password', $data)) {
            $data['password']  = Hash::make($data['password']);
        }

        $user->update($data);

        return response()->json(['user' => $user], 200);
    }

    public function destroy(User $user)
    {
        return response()->json(["delete" => $user->delete()]);
    }

    public function sendUserMail(SendUserMailRequest $request, User $user, SendUserMailService $sendUserMailService)
    {
        $data = $request->validated();

        $mailData = [
            'title' => $data['title'],
            'body' => $data['body']
        ];

        $sendUserMailService->run($mailData, $user->email);

        return response()->json(['message' => 'Email is sent successfully.']);
    }
}
