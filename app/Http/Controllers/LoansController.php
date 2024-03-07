<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use App\Models\MusicSheet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\Loan\LoanRequest;
use App\Http\Resources\Loan\LoanCollection;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\Borrower\BorrowerCollection;
use Illuminate\Foundation\Validation\ValidatesRequests;

class LoansController extends Controller
{
    use ValidatesRequests;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $borrowers = User::query()->whereHas('loans', function($query) {            
            $query->where('status', 'open');
        })->paginate(10);

        return new BorrowerCollection($borrowers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoanRequest $request)
    {
        // try {
        $loan = Loan::query()->create([
            'user_id' => $request->userId,
            'status' => 'open',
            'loan_date' => \Carbon\Carbon::now('utc'),
            'delivery_date' => $request->deliveryDate,
            'quantity' => 0,
            'type' => 'physical',
        ]);

        $cuantity = 0;
        foreach ($request->items as $musicSheet) {
            $loan->musicSheets()->attach($musicSheet["id"], ['quantity' => $musicSheet["cuantity"]]);
            $cuantity += $musicSheet["cuantity"];
        }

        $loan->quantity = $cuantity;
        $loan->save();


        // TODO return json ok
        return response()->json(['message' => 'success'], Response::HTTP_OK);

        // } catch (\Throwable $th) {
        //     return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan  $loans
     * @return \Illuminate\Http\Response
     */
    public function show(Loan $loans)
    {
        return response(['loan' => $loans->jsonSerialize()], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Loan  $loans
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loan $loans)
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
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function returnLoan(Request $request)
    {
        // TODO: refactor cuando las devoluciones tiene 1 prestamo con n partiruras
        $musicSheets = MusicSheet::whereIn('id', $request->musicSheetIds)->get();
        $musicSheets->each(function ($musicSheet) use ($request) {
            $musicSheet->available += $request->cuantity;
            $musicSheet->save();
        });
        Loan::find($request->loanId)->delete();

        return $this->getBorrowerLoans($request->borrowerId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loans
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        // $loans = Loan::where('borrower_id', $id);

        // $loansArray = $loans->get()->all();

        // if ($loansArray) {
        //     $musicSheetsJson = array_map('json_decode', array_column($loansArray, 'music_sheets_borrowed_amount'));
        //     foreach ($musicSheetsJson as $key) {
        //         $keyArray = (array) $key;
        //         foreach ($keyArray as $id => $cuantity) {
        //             $musicSheet = MusicSheet::find($id);
        //             $musicSheet->available += $cuantity;
        //             $musicSheet->save();
        //         }
        //     }
        // }

        // $loans->delete();

        // $borrowers = Borrower::with('loans')->whereHas('loans')->get();

        // return response(['loans' => $borrowers->jsonSerialize()], Response::HTTP_OK);
    }

    public function getBorrowerLoans($id)
    {
        $loans = Loan::where('user_id', $id)->where('status', 'open')->get();

        return new LoanCollection($loans);
    }
}
