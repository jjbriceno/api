<?php

namespace App\Http\Controllers;

use App\Models\Loans;
use App\Models\Borrowers;
use App\Models\MusicSheet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;

class BorrowersController extends Controller
{
    use ValidatesRequests;

    private function Rules($id = '')
    {
        return $id ?
        [
            'id'        => ['required'],
            'name'      => ['required'],
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

    private function Messeges()
    {
        return [
            'firstName.required' => 'El nombre es requerido',
            'lastName.required'  => 'El apellido es requerido',
            'email.required'     => 'El correo electrónico es requerido',
            'email.unique'       => 'El correo electrónico ya se encuentra registrado',
            'phone.unique'       => 'El teléfono ya se encuentra registrado',
            'phone.required'     => 'El teléfono es requerido',
            'phone.unique'       => 'El teléfono ya se encuentra registrado',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['borrowers' => Borrowers::all()]);
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
            $this->Messeges()
        );

        $borrower = new Borrowers();
        $borrower->first_name = $request->firstName;
        $borrower->last_name = $request->lastName;
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
    public function show($id)
    {
        try {
            $borrower = Borrowers::findOrFail($id);
            return response(['borrower' => $borrower->jsonSerialize()], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\borrower  $borrower
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
     * @param  \App\Models\borrower  $borrower
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            $this->Rules($id),
            $this->Messages()
        ]);

        $borrower = Borrowers::findOrFail($id);
        $borrower->first_name = $request->name;
        $borrower->last_name = $request->lastName;
        $borrower->email = $request->email;
        $borrower->phone = $request->phone;
        $borrower->address = $request->address;
        $borrower->save();

        return response(['borrower' => $borrower->jsonSerialize()], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\borrower  $borrower
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            DB::transaction(function () use ($id) {
                $borrower = Borrowers::findOrFail($id);
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
    
                $loans->delete();
    
                $borrower = Borrowers::findOrFail($id);
                $borrower->delete();
                return response()->json(['borrower' => $borrower->jsonSerialize(), 'message' => 'success'], Response::HTTP_OK);
            });
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
