<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ProfilePicture;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Profile\ProfileRequest;
use App\Http\Resources\Profile\ProfileResource;
use App\Http\Resources\Profile\ProfileCollection;
use App\Http\Resources\User\UserResource;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    use ValidatesRequests;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profiles = Profile::query()->paginate(10);

        return new ProfileCollection($profiles);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $profile = Profile::query()->where('user_id', $id)->firstOrFail();

            return new ProfileResource($profile);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateUserProfile(Request $request)
    {
        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = User::where('id', $request->userId)->first();
                $userProfile = Profile::where('user_id', $request->userId)->first();
        
                if ($user->email != $request->email) {
                    $user->email_verified_at = null;
                    $user->sendEmailVerificationNotification();
                }
                $user->email = $request->email;
                $user->name = $request->firstName;
                $user->save();
        
                $userProfile->first_name = $request->firstName;
                $userProfile->last_name = $request->lastName;
                $userProfile->phone = $request->phone;
                $userProfile->address = $request->address;
                $userProfile->save();
            });
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $user = User::where('id', $request->userId)->first();

        return new UserResource($user->load('profile'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Loan  $loans
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileRequest $request, $id)
    {
        try {
            DB::transaction(function () use ($request) {
                $user = $request->user();
                $profile = $user->profile;

                $is_user_email_equal = $user->email == $request->email;
                if (!$is_user_email_equal) {
                    $user->email_verified_at = null;
                }

                $user->email = $request->email;
                $user->name = $request->firstName;
                $user->save();

                if (!$user->hasVerifiedEmail() && !$is_user_email_equal) {
                    $user->sendEmailVerificationNotification();
                }

                if ($request->hasFile('profilePicture')) {
                    $fileName = $user->name ?? $request->file('profilePicture')->getClientOriginalName();
                    $fileFormat = $request->file('profilePicture')->getClientOriginalExtension();

                    $profilePicture = $profile->profile_picture_id
                        ? ProfilePicture::findOrFail($profile->profile_picture_id)
                        : new ProfilePicture();

                    $profilePicture->file_name = $fileName;
                    $profilePicture->file_format = $fileFormat;
                    $profilePicture->binary_file = base64_encode($request->file('profilePicture')->get());
                    $profilePicture->save();
                    $profile->profile_picture_id = $profilePicture->id;
                }

                $profile->update([
                    'first_name' => $request->firstName,
                    'last_name' => $request->lastName,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'user_id' => $user->id,
                ]);
            });

            $user = User::query()->with('profile')->findOrFail($request->user()->id);

            return response()->json(['profile' => new ProfileResource($user->profile), 'user' => new UserResource($user)], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the profile picture in storage.
     */
    public function updateUserProfilePicture(Request $request)
    {
        $file = $request->file('profile_picture');

        // Validate file type and size (optional, can be done on frontend too)
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $extension = Str::lower($file->getClientOriginalExtension());

        if (!in_array($extension, $allowedExtensions)) {
            return response()->json(['errors' => [
                'profilePicture' => ['Tipo de archivo no válido. Solo se aceptan los formatos de archivo jpg, jpeg o png'],
            ]], 422);
        }

        $fileName = uniqid() . '.' . $extension;
        $filePath = 'profile-pictures/' . $fileName;

        // Store the file
        Storage::disk('public')->put($filePath, $file->getContent());

        $user = $request->user();
        $profile = $user->profile;

        if ($profile->profile_picture_id) {
            // Delete previous profile picture
            $profilePicture = ProfilePicture::findOrFail($profile->profile_picture_id);
            Storage::disk('public')->delete('profile-pictures/' . $profilePicture->file_name);

            // Update existing profile picture
            $profilePicture->update(['file_name' => $fileName]);
        } else {
            // Create a new profile picture
            $profilePicture = ProfilePicture::create([
                'file_name' => $fileName,
                'file_format' => $extension,
                'binary_file' => base64_encode($file->get()),
            ]);

            // Update user profile with the new profile picture ID
            $profile->update(['profile_picture_id' => $profilePicture->id]);
        }

        $baseUrl = config('app.url');
        $profilePictureUrl = $baseUrl . '/storage/' . $filePath;

        return response()->json(['profile_picture_url' => $profilePictureUrl]);
    }
}
