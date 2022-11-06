<?php

namespace App\Http\Controllers;

use App\Models\Borrowers;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

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
        $this->validate($request, [
            'firstName' => ['required'],
            'lastName'  => ['required'],
            'email'     => ['nullable', 'unique:borrowers,email'],
            'phone'     => ['required', 'unique:borrowers,phone'],
            'address'   => ['nullable']
        ]);

        $borrower = new Borrowers();
        $borrower->name = $request->firstName . ' ' . $request->lastName;
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
    public function update(Request $request, Borrowers $borrower)
    {


        $borrower->name = $request->name;
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
        Borrowers::destroy($id);

        return response(null, Response::HTTP_OK);
    }
}
