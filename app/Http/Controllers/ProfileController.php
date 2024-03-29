<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $request->user()
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        /** $user \App\Models\User */
        $user = $request->user();

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
            'name' => 'required'
        ]);

        $user->fill([
            'email' => $request->input('email'),
            'name' => $request->input('name')
        ]);
        $user->save();

        return response()->json([
            'data' => $user
        ]);
    }

    public function topics()
    {
       
    }
}
