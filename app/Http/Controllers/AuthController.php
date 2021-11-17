<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'meta' => [
                'token' => $user->createToken($request->input('device_name'))->plainTextToken
            ]
        ]);
    }

    public function register(Request $request, CreateNewUser $creator): JsonResponse
    {
        $this->validate($request, [
            'device_name' => 'required'
        ]);

        event(new Registered($user = $creator->create($request->all())));

        return response()->json([
            'data' => $user,
            'meta' => [
                'token' => $user->createToken($request->input('device_name'))->plainTextToken
            ]
        ], 201);
    }
}
