<?php

namespace App\Http\Controllers;

use App\Models\Lenders;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class LendersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['Lenders' => Lenders::all()]);
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
        $crud = new Lenders();
      
        $crud->name = $request->name;
        $crud->phone = $request->phone;
        $crud->email = $request->email;
        $crud->address  = $request->address;

        $crud->save();

        return response($crud->jsonSerialize(), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lenders  $lenders
     * @return \Illuminate\Http\Response
     */
    public function show(Lenders $lenders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lenders  $lenders
     * @return \Illuminate\Http\Response
     */
    public function edit(Lenders $lenders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lenders  $lenders
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lenders $lenders)
    {
       
      
        $lenders->name = $request->name;
        $lenders->phone = $request->phone;
        $lenders->email = $request->email;
        $lenders->address  = $request->address;
       
        $lenders->save();
        
        return response($lenders , Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lenders  $lenders
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Lenders::destroy($id);

        return response(null, Response::HTTP_OK);
    }
}
