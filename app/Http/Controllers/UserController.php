<?php

namespace App\Http\Controllers;

use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Models\User;

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
}
