<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersController extends Controller
{
    public function index(Request $request): UserCollection
    {
        // exclude current logged in user
        $query = User::query()->where('id', '<>', $request->user()->id);

        $query->when($request->has('with'), function () use ($request, $query) {
            if (is_array($request->input('with'))) {
                $query->with($request->input('with'));
            }
        });

        return new UserCollection($query->paginate($request->input('per_page', 20)));
    }
}
