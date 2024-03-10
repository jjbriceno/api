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
            //Se busca el usuario  de este perfil
            $user = User::query()->findOrFail($request->user()->id);

            DB::transaction(function () use ($user, $request) {
                //Se se buscar el perfil de usuario que se actualizará
                $profile = $user->profile;
                // Sí el email que se actualiza es nuevo se marca como no verificado
                if ($user->email != $request->email) {
                    $user->email_verified_at = null;
                }
                $user->email    = $request->email;
                $user->save();

                if ($request->hasFile('profilePicture')) {
                    $file_nanme     = $user->name ?? $request->file('profilePicture')->getClientOriginalName();
                    $file_format    = $request->file('profilePicture')->getClientOriginalExtension();

                    if ($profile->profile_picture_id) {
                        $profilePicture = ProfilePicture::query()->findOrFail($profile->profile_picture_id);
                    } else {
                        $profilePicture = new ProfilePicture();
                    }

                    $profilePicture->file_name      = $file_nanme;
                    $profilePicture->file_format    = $file_format;
                    $profilePicture->binary_file    = base64_encode($request->file('profilePicture')->get());
                    $profilePicture->save();
                    $profile->profile_picture_id    = $profilePicture->id;
                }

                $profile->first_name    = $request->firstName;
                $profile->last_name     = $request->lastName;
                $profile->phone         = $request->phone;
                $profile->address       = $request->address;
                $profile->user_id       = $user->id;
                $profile->save();
            });

            $profile = User::query()->with('profile')->findOrFail($request->user()->id)->profile;

            return response(['profile' => new ProfileResource($profile)], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


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

        Storage::disk('public')->put('profile-pictures/' . $fileName, $file->getContent());

        // Update user's profile picture in database with the filename
        $profilePicture                 = new ProfilePicture();
        $profilePicture->file_name      = $fileName;
        $profilePicture->file_format    = $extension;
        $profilePicture->binary_file    = base64_encode($file->get());
        $profilePicture->save();
        $request->user()->profile->profile_picture_id = $profilePicture->id;
        $request->user()->profile->save();

        $baseUrl = config('app.url'); // Or get it from environment variables
        $profilePictureUrl = $baseUrl . '/storage/profile-pictures/' . $fileName; // Construct the URL

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
