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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileRequest $request)
    {
        //
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        //
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

                if ($user->email != $request->email) {
                    $user->email_verified_at = null;
                }

                $user->email = $request->email;
                $user->name = $request->firstName;
                $user->save();

                if (!$user->hasVerifiedEmail()) {
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
        $extension = $file->getClientOriginalExtension();

        if (!in_array($extension, $allowedExtensions)) {
            return response()->json(['error' => 'Invalid file type'], 422);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loans
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
