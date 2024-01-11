<?php

namespace App\Http\Controllers;

<<<<<<< Updated upstream
use App\Models\Loans;
use App\Models\Borrowers;
use App\Models\MusicSheet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
=======
use App\Models\Borrower;
use App\Models\MusicSheet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Borrower\BorrowerCollection;
>>>>>>> Stashed changes
use Illuminate\Foundation\Validation\ValidatesRequests;

class BorrowersController extends Controller
{
    use ValidatesRequests;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
<<<<<<< Updated upstream
        return response()->json(['borrowers' => Borrowers::all()]);
=======
        $Borrowers = Borrower::filtered()->paginate(5);

        return new BorrowerCollection($Borrowers);
>>>>>>> Stashed changes
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
    public function store(Request $request)
    {
<<<<<<< Updated upstream
        $this->validate($request, [
            'firstName' => ['required'],
            'lastName'  => ['required'],
            'email'     => ['nullable', 'unique:borrowers,email'],
            'phone'     => ['required', 'unique:borrowers,phone'],
            'address'   => ['nullable']
        ]);

        $borrower = new Borrowers();
        $borrower->name = $request->firstName . ' ' . $request->lastName;
=======
        $this->validate(
            $request,
            $this->Rules(),
            $this->Messages()
        );
        
        $borrower = new Borrower();
        $borrower->first_name = $request->firstName;
        $borrower->last_name = $request->lastName;
>>>>>>> Stashed changes
        $borrower->email = $request->email;
        $borrower->phone = $request->phone;
        $borrower->address  = $request->address;
        $borrower->save();

        return response(['borrower' => $borrower->jsonSerialize()], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\borrower  $borrower
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
<<<<<<< Updated upstream
=======
        try {
            $borrower = Borrower::findOrFail($id);
            return response(['borrower' => $borrower->jsonSerialize()], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
>>>>>>> Stashed changes
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\borrower  $borrower
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $this->validate($request, [
            'id'        => ['required'],
            'name'      => ['required'],
            'lastName'  => ['required'],
            'email'     => ['nullable', 'unique:borrowers,email,' . $request->id],
            'phone'     => ['required', 'unique:borrowers,phone,' . $request->id],
            'address'   => ['nullable']
        ]);

        $borrower = Borrowers::find($request->id);
        $borrower->name = $request->name . ' ' . $request->lastName;
        $borrower->email = $request->email;
        $borrower->phone = $request->phone;
        $borrower->address = $request->address;
        $borrower->save();

        return response(['borrower' => $borrower->jsonSerialize()], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\borrower  $borrower
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Borrower $borrower)
    {
<<<<<<< Updated upstream


        $borrower->name = $request->name;
=======
        $this->validate(
            $request,
            $this->Rules($borrower->id),
            $this->Messages()
        );

        $borrower = Borrower::findOrFail($borrower->id);
        $borrower->first_name = $request->firstName;
        $borrower->last_name = $request->lastName;
        $borrower->email = $request->email;
>>>>>>> Stashed changes
        $borrower->phone = $request->phone;
        $borrower->email = $request->email;
        $borrower->address  = $request->address;

        $borrower->save();

        return response($borrower, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\borrower  $borrower
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
<<<<<<< Updated upstream
=======
        try {
            DB::transaction(function () use ($id) {
                $borrower = Borrower::findOrFail($id);
                $loans = $borrower->loans();
                // $loans = Loans::where('borrower_id', $id);

                $loansArray = $loans->get()->all();

                if ($loansArray) {
                    $musicSheetsJson = array_map('json_decode', array_column($loansArray, 'music_sheets_borrowed_amount'));
                    foreach ($musicSheetsJson as $key) {
                        $keyArray = (array) $key;
                        foreach ($keyArray as $id => $cuantity) {
                            $musicSheet = MusicSheet::findOrFail($id);
                            $musicSheet->available += $cuantity;
                            $musicSheet->save();
                        }
                    }
                }
>>>>>>> Stashed changes

        $loans = Loans::where('borrower_id', $id);

<<<<<<< Updated upstream
        $loansArray = $loans->get()->all();

        if ($loansArray) {
            $musicSheetsJson = array_map('json_decode', array_column($loansArray, 'music_sheets_borrowed_amount'));
            foreach ($musicSheetsJson as $key) {
                $keyArray = (array) $key;
                foreach ($keyArray as $id => $cuantity) {
                    $musicSheet = MusicSheet::find($id);
                    $musicSheet->available += $cuantity;
                    $musicSheet->save();
                }
            }
=======
                $borrower = Borrower::findOrFail($id);
                $borrower->delete();
                return response()->json(['borrower' => $borrower->jsonSerialize(), 'message' => 'success'], Response::HTTP_OK);
            });
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function search()
    {
        if (request('search')) {
            $musicSheet = Borrower::search()->paginate(5);
            return new BorrowerCollection($musicSheet);
        } else {
            return $this->index();
>>>>>>> Stashed changes
        }

        $loans->delete();

        Borrowers::destroy($id);

        return response(null, Response::HTTP_OK);
    }
}
