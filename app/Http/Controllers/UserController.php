<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    /**
     * Returns the list of all users paging them.
     */
    public function index()
    {
        $users = User::query()->with('roles')->paginate(10);

        return new UserCollection($users);
    }

    public function getUsers() {

        $users = User::query()->get();

        return new UserCollection($users);
    }

    // public function getUsersWithActiveLoans()
    // {
    //     $users = User::query()->whereHas('loans', function($query) {
    //         $query->whereHas('musicSheets')->with('musicSheets');
    //     })->with('loans')->paginate(10);
        
    //     return new UserCollection($users);
    // }

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

    public function destroy($id)
    {
        try{
            $user = User::query()->findOrFail($id);
            // Check if the user has loans
            if ($user->loans()->count() > 0) {
                return response()
                    ->json(['errors' => ['Este usuario no se puede eliminar, debido a que posee prestamos asociados.']],
                    Response::HTTP_FORBIDDEN);
            }
            DB::transaction(function () use ($user) {
                $user->delete();
            });
            return response()->json(['message' => 'success'], Response::HTTP_OK);
    
        } catch (\Throwable $th) {

            if ($th instanceof ModelNotFoundException) {
                return response()->json(['errors' => ['No se encontró el usuario que intenta eliminar']],
                    Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return response()->json(['errors' => [Lang::get($th->getMessage())]], Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }

    public function search()
    {
        if (request('search')) {
            $users = User::search()->with('roles')->paginate(10);
            return new UserCollection($users);
        } else {
            return $this->index();
        }
    }
}
