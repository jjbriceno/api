<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Gender;
use App\Models\Cabinets;
use App\Models\Drawers;
use App\Models\Locations;
use App\Models\MusicSheet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MusicSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['music_sheet' => MusicSheet::all()]);
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
        $this->validate(
            $request,
            [
                'title'         => ['required'],
                'authorId'    => ['required'],
                'genderId'    => ['required'],
                'drawerId'    => ['required'],
                'cabinetId'   => ['required'],
                'cuantity'      => ['required']
            ]
        );

        $musicSheet = new MusicSheet();
        $musicSheet->title = $request->title;
        $musicSheet->author_id = $request->authorId;
        $musicSheet->gender_id = $request->genderId;
        $musicSheet->title = $request->title;
        $musicSheet->cuantity = $request->cuantity;

        $location = new Locations();
        $location->cabinet_id = $request->cabinetId;
        $location->drawer_id = $request->drawerId;
        $location->save();

        $musicSheet->location_id = $location->id;
        $musicSheet->save();

        return response()->json(['item' => MusicSheet::find($musicSheet->id), 'message' => 'success']);
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
