<?php

namespace App\Http\Controllers;

use App\Models\MusicSheet;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class MusicSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['MusicSheet' => MusicSheet::all()]);
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
        $crud = new MusicSheet();
     
        $crud->author_id = $request->author_id;
        $crud->gender_id = $request->gender_id;
        $crud->location_id = $request->location_id;
        $crud->title = $request->title;
        $crud->cuantity = $request->cuantity;
      

        $crud->save();

        return response($crud->jsonSerialize(), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MusicSheet  $musicSheet
     * @return \Illuminate\Http\Response
     */
    public function show(MusicSheet $musicSheet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MusicSheet  $musicSheet
     * @return \Illuminate\Http\Response
     */
    public function edit(MusicSheet $musicSheet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MusicSheet  $musicSheet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MusicSheet $musicSheet)
    {
        $musicSheet->author_id = $request->author_id;
        $musicSheet->gender_id = $request->gender_id;
        $musicSheet->location_id = $request->location_id;
        $musicSheet->title = $request->title;
        $musicSheet->cuantity = $request->cuantity;
      

        $musicSheet->save();
  
 

        return response($musicSheet, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MusicSheet  $musicSheet
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MusicSheet::destroy($id);

        return response(null, Response::HTTP_OK);
    }
}
