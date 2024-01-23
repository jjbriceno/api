<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use App\Models\MusicSheet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Borrower\BorrowerResource;
use App\Http\Resources\Borrower\BorrowerCollection;
use Illuminate\Foundation\Validation\ValidatesRequests;

class BorrowersController extends Controller
{
    use ValidatesRequests;

    private function Rules($id = '')
    {
        return $id ?
            [
                'id'        => ['required'],
                'firstName' => ['required'],
                'lastName'  => ['required'],
                'email'     => ['required', 'unique:borrowers,email,' . $id],
                'phone'     => ['required', 'unique:borrowers,phone,' . $id],
                'address'   => ['nullable']
            ]
            : [
                'firstName' => ['required'],
                'lastName'  => ['required'],
                'email'     => ['required', 'unique:borrowers,email'],
                'phone'     => ['required', 'unique:borrowers,phone'],
                'address'   => ['nullable']
            ];
    }

    private function Messages()
    {
        return [
            'firstName.required' => 'El nombre es requerido',
            'lastName.required'  => 'El apellido es requerido',
            'email.required'     => 'El correo electrónico es requerido',
            'email.unique'       => 'El correo electrónico ya se encuentra registrado',
            'phone.unique'       => 'El teléfono ya se encuentra registrado',
            'phone.required'     => 'El teléfono es requerido',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $borrowers = Borrower::paginate(5);

        return new BorrowerCollection($borrowers);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBorrowers()
    {
        $borrowers = Borrower::query()->whereHas('loans')->with('loans')->paginate(10);

        return new BorrowerCollection($borrowers);
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
        $this->validate(
            $request,
            $this->Rules(),
            $this->Messages()
        );

        $borrower = new Borrower();
        $borrower->first_name = $request->firstName;
        $borrower->last_name = $request->lastName;
        $borrower->email = $request->email;
        $borrower->phone = $request->phone;
        $borrower->address  = $request->address;
        $borrower->save();

        return new BorrowerResource($borrower);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Borrower  $Borrower
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $borrower = Borrower::findOrFail($id);
            return new BorrowerResource($borrower);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Borrower  $Borrower
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Borrower  $Borrower
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Borrower $borrower)
    {
        $this->validate(
            $request,
            $this->Rules($borrower->id),
            $this->Messages()
        );

        $borrower = Borrower::findOrFail($borrower->id);
        $borrower->first_name = $request->firstName;
        $borrower->last_name = $request->lastName;
        $borrower->email = $request->email;
        $borrower->phone = $request->phone;
        $borrower->address = $request->address;
        $borrower->save();

        return new BorrowerResource($borrower);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Borrower  $Borrower
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $Borrower = Borrower::findOrFail($id);
                $loans = $Borrower->loans();
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

                $loans->delete();

                $Borrower = Borrower::findOrFail($id);
                $Borrower->delete();
                return response()->json(['Borrower' => $Borrower->jsonSerialize(), 'message' => 'success'], Response::HTTP_OK);
            });
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function search()
    {
        if (request('search')) {
            $musicSheet = Borrower::search()->paginate(10);
            return new BorrowerCollection($musicSheet);
        } else {
            return $this->index();
        }
    }
}
