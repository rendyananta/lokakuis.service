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
    /**
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::query()->where('email', $request->input('email'))->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->makeVisible([
            'two_factor_secret',
            'two_factor_recovery_codes',
            'email_verified_at'
        ]);

        return response()->json([
            'data' => $user,
            'meta' => [
                'token' => $user->createToken($request->input('device_name'))->plainTextToken
            ]
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function register(Request $request, CreateNewUser $creator): JsonResponse
    {
        $this->validate($request, [
            'device_name' => 'required'
        ]);

        event(new Registered($user = $creator->create($request->all())));

        $user->makeVisible([
            'two_factor_secret',
            'two_factor_recovery_codes',
            'email_verified_at'
        ]);

        return response()->json([
            'data' => $user,
            'meta' => [
                'token' => $user->createToken($request->input('device_name'))->plainTextToken
            ]
        ], 201);
    }
}
