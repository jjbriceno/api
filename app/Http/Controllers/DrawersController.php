<?php

namespace App\Http\Controllers;

use App\Models\Drawers;
use Illuminate\Http\Request;

class DrawersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['drawers' => Drawers::all()]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Drawers  $drawers
     * @return \Illuminate\Http\Response
     */
    public function show(Drawers $drawers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Drawers  $drawers
     * @return \Illuminate\Http\Response
     */
    public function edit(Drawers $drawers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Drawers  $drawers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Drawers $drawers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Drawers  $drawers
     * @return \Illuminate\Http\Response
     */
    public function destroy(Drawers $drawers)
    {
        //
    }
}
