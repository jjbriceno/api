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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $borrower = new User();
        // $borrower->first_name = $request->firstName;
        // $borrower->last_name = $request->lastName;
        // $borrower->email = $request->email;
        // $borrower->phone = $request->phone;
        // $borrower->address  = $request->address;
        // $borrower->save();

        // return new BorrowerResource($borrower);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Borrower  $Borrower
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // try {
        //     $borrower = User::findOrFail($id);
        //     return new BorrowerResource($borrower);
        // } catch (\Throwable $th) {
        //     return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        // }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Borrower  $Borrower
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $borrower)
    {
        // $this->validate(
        //     $request,
        //     $this->Rules($borrower->id),
        //     $this->Messages()
        // );

        // $borrower = User::findOrFail($borrower->id);
        // $borrower->first_name = $request->firstName;
        // $borrower->last_name = $request->lastName;
        // $borrower->email = $request->email;
        // $borrower->phone = $request->phone;
        // $borrower->address = $request->address;
        // $borrower->save();

        // return new BorrowerResource($borrower);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Borrower  $Borrower
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // try {
        //     DB::transaction(function () use ($id) {
        //         $Borrower = User::findOrFail($id);
        //         $loans = $Borrower->loans();
        //         // $loans = Loan::where('borrower_id', $id);

        //         $loansArray = $loans->get()->all();

        //         if ($loansArray) {
        //             $musicSheetsJson = array_map('json_decode', array_column($loansArray, 'music_sheets_borrowed_amount'));
        //             foreach ($musicSheetsJson as $key) {
        //                 $keyArray = (array) $key;
        //                 foreach ($keyArray as $id => $cuantity) {
        //                     $musicSheet = MusicSheet::findOrFail($id);
        //                     $musicSheet->available += $cuantity;
        //                     $musicSheet->save();
        //                 }
        //             }
        //         }

        //         $loans->delete();

        //         $Borrower = User::findOrFail($id);
        //         $Borrower->delete();
        //         return response()->json(['Borrower' => $Borrower->jsonSerialize(), 'message' => 'success'], Response::HTTP_OK);
        //     });
        // } catch (\Throwable $th) {
        //     return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        // }
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
