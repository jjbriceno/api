<?php

namespace App\Http\Controllers\Auth;

use App\Events\Profile\UserProfile;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\RegisterRequest;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if($user) {
            $user->assignRole("user");
        }

        event(new Registered($user));

        event(new UserProfile($user->id, $request->only(['firstName', 'lastName'])));

        // Auth::login($user);
        $user->assingRole('user');

        $user->tokens()->delete();

        return response()->json([]);
    }
}
