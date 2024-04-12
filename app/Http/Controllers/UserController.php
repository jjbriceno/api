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
use App\Http\Resources\Borrower\BorrowerCollection;
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

    public function getUsers()
    {

        $users = User::query()->get();

        return new UserCollection($users);
    }

    public function getUsersWithActiveLoans()
    {
        $borrowers = User::query()->whereHas('loans', function ($query) {
            $query->where('status', 'open');
        })->paginate(10);

        return new BorrowerCollection($borrowers);
    }

    public function getUsersWithLoans(Request $request)
    {
        try {
            $user = User::query()->query()->whereHas('loans', function ($query) use ($request) {
                $query->where('status', $query->status)
                    ->where('type', $query->type);
            });

            if ($request->user()->hasRole('admin')) {
                $borrowers = $user->get();
            }
            return new BorrowerCollection($borrowers);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $borrowers = User::query()->whereHas('loans', function ($query) {
            $query->where('status', 'open');
        })->paginate(10);
    }
    public function changeUserRole(Request $request)
    {
        try {
            $user = User::query()->findOrFail($request->id);

            $user->syncRoles($user->hasRole('admin') ? ['user'] : ['admin']);

            $user->sendRoleChangeNotification();

            return new UserResource($user->load('roles'));
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['errors' => [Lang::get($th->getMessage())]], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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

    public function destroy($id)
    {
        try {
            $user = User::query()->findOrFail($id);
            // Check if the user has loans
            if ($user->loans()->count() > 0) {
                return response()
                    ->json(
                        ['errors' => ['deleteError' => ['Este usuario no se puede eliminar, debido a que posee prestamos asociados.']]],
                        422
                    );
            }
            
            DB::transaction(function () use ($user) {
                $user->delete();
            });
            
            $user->sendDeletedUserNotification();
            
            return response()->json(['message' => 'success'], Response::HTTP_OK);
        } catch (\Throwable $th) {

            if ($th instanceof ModelNotFoundException) {
                return response()->json(
                    ['errors' => ['No se encontró el usuario que intenta eliminar']],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
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

    public function searchUsersWithActiveLoans()
    {
        if (request('search')) {
            $borrowers = User::SearchUsersWithActiveLoans()->paginate(10);
            return new BorrowerCollection($borrowers);
        } else {
            return $this->getUsersWithActiveLoans();
        }
    }
}
