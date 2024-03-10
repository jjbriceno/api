<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use App\Http\Requests\EmailVerification\EmailVerificationRequest;
use App\Http\Resources\User\UserResource;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        $user = User::find($request->user()->id);

        if ($user->hasVerifiedEmail()) {
            return response()->json(["path" => '/dashboard', "user" => new UserResource($user->load('roles.permissions'))], 200);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json(["path" => '/dashboard', "user" => new UserResource($user->load('roles.permissions'))], 200);
    }
}
