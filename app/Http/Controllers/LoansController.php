<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Borrower;
use App\Models\MusicSheet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Loan\LoanRequest;
use App\Events\Loan\NewLoanRegisterEvent;
use App\Http\Resources\Loan\LoanResource;
use App\Http\Resources\MusicSheetCollection;
use App\Http\Resources\MusicSheetResource;
use App\Http\Requests\Loan\AddToCartRequest;
use App\Http\Resources\Borrower\BorrowerCollection;
use Carbon\Carbon;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;

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
        $borrowers = Borrower::query()->whereHas('loans', function ($query) {
            $query->whereHas('musicSheets');
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
        $loan = Loan::query()->create([
            'borrower_id' => $request->borrowerId,
            'status' => 'open',
            'loan_date' => \Carbon\Carbon::now('utc'),
            'delivery_date' => $request->deliveryDate,
            'cuantity' => $request->cuantity
        ]);

        $loan->musicSheets()->attach($request->musicSheetId, ['cuantity' => $request->cuantity]);
        event(new NewLoanRegisterEvent($request->musicSheetId, $request->cuantity));

        // TODO return
        return new MusicSheetResource(MusicSheet::find($request->musicSheetId));
        // try {
        //     $loan = Loan::query()->create([
        //         'borrower_id' => $request->borrowerId,
        //         'status' => 'open',
        //         'loan_date' => \Carbon\Carbon::now('utc'),
        //         'delivery_date' => $request->deliveryDate,
        //         'cuantity' => 0
        //     ]);

        //     $cuantity = 0;
        //     $arraySheets = [];
        //     foreach ($request->musicSheet as $sheet) {
        //         $loan->musicSheets()->attach($sheet->id, ['cuantity' => $sheet->cuantity]);
        //         $arraySheets[] = $sheet->id;
        //         $cuantity += $sheet->cuantity;
        //         event(new NewLoanRegisterEvent($sheet->id, $sheet->cuantity));
        //     }

        //     $loan->cuantity = $cuantity;
        //     $loan->save();

        //     $musicSheets = MusicSheet::query()->whereIn('id', $arraySheets)->get();
    
        //     // TODO return
        //     return response(['loans' => new MusicSheetCollection($musicSheets)], Response::HTTP_OK);

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
    public function update(LoanRequest $request, $id)
    {
        try {
            $loan = Loan::findOrFail($id);
            $loan->delivery_date = $request->deliveryDate;
            $loan->borrower_id = $request->borrowerId;

            //Se eliminan todas las relacións de las partituras asociadas al prestamo a actualizar
            $loan->musicSheets()->detach();

            $cuantity = 0;
            $arraySheetsIds = [];

            //Se resgistran las nuevas partituras asociadas al prestamo.
            foreach ($request->musicSheet as $sheet) {
                $loan->musicSheets()->attach($sheet->id, ['cuantity' => $sheet->cuantity]);
                $arraySheetsIds[] = $sheet->id;
                $cuantity += $sheet->cuantity;
                event(new NewLoanRegisterEvent($sheet->id, $sheet->cuantity));
            }

            $loan->cuantity = $cuantity;
            $loan->save();
            
            return response(['loan' => new LoanResource($loan)], Response::HTTP_OK);

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function returnLoan(Request $request)
    {
        try {
            // TODO: refactor cuando las devoluciones tiene 1 prestamo con n partiruras
            $loan = Loan::findOrFail($request->loanId);
            DB::transaction(function () use ($loan) {
                $loan->musicSheets()->each(function ($musicSheet) {
                    $musicSheet->available += $musicSheet->pivot->cuantity;
                    $musicSheet->save();
                });
    
                $loan->status = 'closed';
                $loan->save();
            });

            return response(['loan' => $this->getBorrowerLoans($request->borrowerId)], Response::HTTP_OK);

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loans
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $loans = Loan::query()->findOrFail($id);

            $loansMusicSheets = $loans->musicSheets();
        
            $loansMusicSheets->each(function ($musicSheet) {
                $musicSheet->available += $musicSheet->pivot->cuantity;
                $musicSheet->save();
            });

            $loans->musicSheets()->detach();
            $loans->delete();

            $borrowers = Borrower::with('loans')->whereHas('loans')->get();

            return response(['loans' => new BorrowerCollection($borrowers)], Response::HTTP_OK);

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getBorrowerLoans($id)
    {
        $loans = Loan::where('borrower_id', $id)->get();

        return LoanResource::collection($loans);
    }
}
