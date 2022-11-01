<?php

namespace App\Http\Controllers;

use App\Models\Loans;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class LoansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['loans' => Loans::all()]);
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
        $crud = new Loans();
     
        $crud->lender_id = $request->lender_id;
        $crud->status = $request->status;
        $crud->loan_date = $request->loan_date;
        $crud->delivery_date = $request->delivery_date;
        $crud->music_sheets_borrowed_amount = $request->music_sheets_borrowed_amount;
        $crud->cuantity = $request->cuantity;
        $crud->status = $request->status;
     
        $crud->loan_date = $request->loan_date;
     
        $crud->delivery_date = $request->delivery_date;
     
        $crud->music_sheets_borrowed_amount = $request->music_sheets_borrowed_amount;
     

        $crud->save();

        return response($crud->jsonSerialize(), Response::HTTP_CREATED);
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
        Loans::destroy($id);

        return response(null, Response::HTTP_OK);
    }
}
