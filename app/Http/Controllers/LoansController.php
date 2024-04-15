<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Loan\LoanRequest;
use App\Http\Resources\Calendar\CalendarResourceCollection;
use App\Http\Resources\Loan\LoanCollection;
use App\Http\Resources\MusicSheet\LoanMusicSheetCollection;
use Carbon\Carbon;
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
        $user = auth()->user();

        $loans = User::query()->where('id', $user->id)->paginate(10);

        return new LoanCollection($loans);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoanRequest $request)
    {
        try {
            $loan = Loan::query()->create([
                'user_id' => $request->userId,
                'loan_date' => \Carbon\Carbon::now('utc'),
                'delivery_date' => $request->deliveryDate ?? null,
                'quantity' => 0,
                'status' => $request->type == 'digital' ? 'requested' : 'open',
                'type' => $request->type
            ]);

            $quantity = 0;
            foreach ($request->items as $musicSheet) {
                $loan->musicSheets()->attach($musicSheet["id"], ['quantity' => $musicSheet["quantity"]]);
                $quantity += $musicSheet["quantity"];
            }

            $loan->quantity = $quantity;
            $loan->save();


            // TODO return json ok
            return response()->json(['message' => 'success'], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan  $loans
     * @return \Illuminate\Http\Response
     */
    public function getAllActiveLoans(Request $request)
    {
        $loans = Loan::query()
        ->where('status', 'open')
        ->whereBetween('delivery_date', [$request->start, $request->end])
        ->leftJoin('profiles', 'profiles.id', '=', 'loans.user_id')
        ->select('loans.*', 'profiles.first_name', 'profiles.last_name')
        ->get();

        return new CalendarResourceCollection($loans);
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
        // TODO: agregar en la solicitud el id del usuario.
        try {
            $loan = Loan::findOrFail($request->loanId);

            DB::transaction(function () use ($loan) {
                $loan->musicSheets()->each(function ($musicSheet) {
                    $musicSheet->available += $musicSheet->pivot->quantity;
                    $musicSheet->save();
                });

                $loan->status = 'closed';
                $loan->save();
            });

            return $this->getBorrowerLoans($request->borrowerId);
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
            $loan = Loan::findOrFail($id);

            DB::transaction(function () use ($loan) {
                $loan->musicSheets()->each(function ($musicSheet) {
                    $musicSheet->available += $musicSheet->pivot->quantity;
                    $musicSheet->save();
                });
                $loan->musicSheets()->detach();
                $loan->status = 'closed';
                $loan->save();
                $loan->delete();
            });

            return $this->getBorrowerLoans($loan->user_id);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getBorrowerLoans($id)
    {
        try {
            $loans = Loan::query()->where('user_id', $id)->where('status', 'open')->get();

            return new LoanCollection($loans);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getLoanMusicSheets($loanId)
    {
        try {
            $loan = Loan::query()->findOrFail($loanId);

            $musicSheets = $loan->musicSheets;

            return new LoanMusicSheetCollection($musicSheets);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieves loans based on the user's role and optional status filter.
     *
     * @param Request $request The HTTP request containing optional status filter
     * @return LoanCollection Collection of loans based on user's role and status filter
     * @throws \Throwable When an internal server error occurs
     */
    public function getLoans(Request $request)
    {
        try {
            $user = auth()->user();
            $loans = Loan::query()->where('status', $request->status)->where('type', $request->type);

            if ($user->hasRole('admin')) {
                $loans = $loans->get();
            } elseif ($user->hasRole('user')) {
                $loans = $loans->where('user_id', $user->id)->get();
            }

            return new LoanCollection($loans);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    function changeLoanStatus(Request $request)
    {
        try {
            $loan = Loan::query()
                ->where('id', $request->loanId)
                ->where('type', 'digital')
                ->where('status', 'requested')
                ->firstOrFail();

            DB::transaction(function () use ($loan, $request) {
                $loan->status = $request->status;
                $loan->save();
                //TODO: Enviar notificación por email con el estatus del prestamo.
                /**
                 * TODO: Dar los permisos temporales para descargar las partituras dentro del présstamo digital
                 * si este ha sido aprobado
                 */
            });

            return $this->getBorrowerLoans($loan->user_id);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function search()
    {
        if (request('search')) {
            $loans = Loan::search()->paginate(10);
            return new LoanCollection($loans);
        } else {
            return $this->index();
        }
    }
}
