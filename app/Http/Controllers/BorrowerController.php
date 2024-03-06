<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MusicSheet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Borrower\BorrowerResource;
use App\Http\Resources\Borrower\BorrowerCollection;
use Illuminate\Foundation\Validation\ValidatesRequests;

class BorrowerController extends Controller
{
    use ValidatesRequests;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $borrowers = User::whereHas('profile')->with('profile')->paginate(10);

        return new BorrowerCollection($borrowers);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBorrowers()
    {
        $borrowers = User::query()->whereHas('loans', function($query) {
            $query->whereHas('musicSheets')->with('musicSheets');
        })->with('loans')->paginate(10);


        return new BorrowerCollection($borrowers);
    }

    public function search()
    {
        if (request('search')) {
            $musicSheet = User::search()->paginate(10);
            return new BorrowerCollection($musicSheet);
        } else {
            return $this->index();
        }
    }
}
