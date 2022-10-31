<?php

namespace App\Http\Controllers;

use App\Models\Cabinets;
 
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class CabinetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['Cabinets' =>Cabinets::all()]);
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
        $crud = new Cabinets();
        $crud->name = $request->name;

        $crud->save();

        return response($crud->jsonSerialize(), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cabinets  $cabinets
     * @return \Illuminate\Http\Response
     */
    public function show(Cabinets $cabinets)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cabinets  $cabinets
     * @return \Illuminate\Http\Response
     */
    public function edit(Cabinets $cabinets)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cabinets  $cabinets
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cabinets $cabinets)
    {
        $cabinets->name = $request->full_name;
  
        $cabinets->save();

        return response($cabinets, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cabinets  $cabinets
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        cabinets::destroy($id);

        return response(null, Response::HTTP_OK);
    }
}
