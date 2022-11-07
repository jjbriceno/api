<?php

namespace App\Http\Controllers;

use App\Models\Loans;
use App\Models\Borrowers;
use App\Models\MusicSheet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;

class LoansController extends Controller
{
    use ValidatesRequests;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $loans = Loans::all()->where('status', 'abierto')->groupBy(function ($item) {
        //     return Borrowers::where('id', $item->borrower_id)->first()->name;
        // });

        $borrowers = Borrowers::with('loans')->whereHas('loans')->get();

        foreach ($borrowers as $borrower) {
            $borrower['total_music_sheets'] = array_sum(array_column($borrower->loans->all(), 'cuantity'));
        }
        return response()->json(['loans' => $borrowers->jsonSerialize()]);
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

        $this->validate($request, [
            'id'            => ['required'],
            'title'         => ['required'],
            'authorId'      => ['required'],
            'genderId'      => ['required'],
            'locationId'    => ['required'],
            'drawerId'      => ['required'],
            'cabinetId'     => ['required'],
            'cuantity'      => ['required'],
            'borrowerId'    => ['required'],
            'deliveryDate'  => ['required']
        ]);

        $loan = new Loans();
        $loan->borrower_id = $request->borrowerId;
        $loan->status = 'abierto';
        $loan->loan_date = \Carbon\Carbon::now('utc')->format('m-d-Y');
        $loan->delivery_date = $request->deliveryDate;
        $loan->music_sheets_borrowed_amount = json_encode([$request->id => $request->cuantity]);
        $loan->cuantity = $request->cuantity;
        $loan->save();

        return response($loan->jsonSerialize(), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loans  $loans
     * @return \Illuminate\Http\Response
     */
    public function show(Loans $loans)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Loans  $loans
     * @return \Illuminate\Http\Response
     */
    public function edit(Loans $loans)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Loans  $loans
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loans $loans)
    {

        $loans->lender_id = $request->lender_id;
        $loans->status = $request->status;
        $loans->loan_date = $request->loan_date;
        $loans->delivery_date = $request->delivery_date;
        $loans->music_sheets_borrowed_amount = $request->music_sheets_borrowed_amount;
        $loans->cuantity = $request->cuantity;
        $loans->status = $request->status;

        $loans->loan_date = $request->loan_date;

        $loans->delivery_date = $request->delivery_date;

        $loans->music_sheets_borrowed_amount = $request->music_sheets_borrowed_amount;


        $loans->save();
        return response($loans, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loans  $loans
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $loans = Loans::where('borrower_id', $id);

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
        }

        $loans->delete();

        $borrowers = Borrowers::with('loans')->whereHas('loans')->get();

        return response(['loans' => $borrowers->jsonSerialize()], Response::HTTP_OK);
    }
}
