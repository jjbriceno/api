<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserCollection;

class UserController extends Controller
{
    /**
     * Returns the list of all users
     */
    public function index()
    {
        $users = User::paginate(10);

        return new UserCollection($users);
    }

    public function getUsersWithActiveLoans()
    {
        $users = User::query()->whereHas('loans', function($query) {
            $query->whereHas('musicSheets')->with('musicSheets');
        })->with('loans')->paginate(10);
        
        return new UserCollection($users);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]); 
        
        $user = $request->user();

        $user->forceFill([
            'password' => Hash::make($validated["password"]),
            'remember_token' => Str::random(60),
        ])->save();

        return response()->json(['status' => __('auth.password_updated')]);
    }
}
