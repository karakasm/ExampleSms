<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Console\Input\Input;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'username' => ['required', 'min:3'],
            'password' => ['required', 'min:8'],
        ]);

        if (!Auth::attempt($validatedData)) {
            return response()->json([
                'status' => 'Authorization Error',
                'code' => '401',
                'message' => 'User not found.'
            ], 401);
        }
        $user = Auth::user();

        $req = Request::create('api/generate-token', 'GET');
        $res = Route::dispatch($req);

        return response()->json([
            'data' => [
                'id' => $user->id,
                'type' => 'user',
                'attributes' => [
                    'username' => $user->username,
                ],
            ],
            'authorization' => [
                'token' => $res->getContent(),
                'type' => 'bearer'
            ],
            'status' => 'success',
            'code' => '200',
            'message' => 'Logged in.',
        ], 200);

    }

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {

        $validatedData = $request->validate([
            'username' => ['required', 'unique:users', 'min:3'],
            'password' => ['required', 'min:8'],
        ]);


        $user = User::create([
            'username' => $validatedData['username'],
            'password' => Hash::make($validatedData['password']),
        ]);

        Auth::login($user);

        $req = Request::create('api/generate-token', 'GET');
        $res = Route::dispatch($req);


        return response()->json([
            'data' => [
                'id' => (string)$user->id,
                'type' => 'user',
                'attributes' => [
                    'username' => $user->username
                ],
            ],
            'authorization' => [
                'token' => $res->getContent(),
                'type' => 'bearer',
            ],
            'status' => 'success',
            'code' => '201',
            'message' => 'User created successfully.',
        ], 201);

    }
}
