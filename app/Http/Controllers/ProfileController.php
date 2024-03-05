<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\ProfileRequest;
use App\Http\Resources\Profile\ProfileCollection;
use App\Http\Resources\Profile\ProfileResource;
use App\Models\Profile;
use App\Models\ProfilePicture;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;

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
            return response(['profile' => new ProfileResource($profile)], Response::HTTP_OK);
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
