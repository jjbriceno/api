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
    protected array $rules;
    protected array $messages;

    public function __construct() {
       $this->rules = [
                        'title'         => ['required', 'unique_with:music_sheets, authorId = author_id'],
                        'authorId'      => ['required'],
                        'genderId'      => ['required'],
                        'drawerId'      => ['required'],
                        'cabinetId'     => ['required'],
                        'cuantity'      => ['required']
       ];

       $this->messages = [
                            'title.required'         => "El 'Título' es obligatorio",
                            'title.unique_with'      => "Este Título ya ha sido registrado con este autor",
                            'authorId.required'      => "El 'Autor' es obligatorio",
                            'genderId.required'      => "El 'Género musical' es obligatorio",
                            'drawerId.required'      => "El 'Estante' es obligatorio",
                            'cabinetId.required'     => "La 'Gaveta' es obligatoria",
                            'cuantity.required'      => "La 'Cantidad de partiruras' debe ser de al menos uno"
       ];
    }
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
            $this->rules, $this->messages
        );

        $musicSheet = new MusicSheet();
        $musicSheet->title = $request->title;
        $musicSheet->author_id = $request->authorId;
        $musicSheet->gender_id = $request->genderId;
        $musicSheet->cuantity = $request->cuantity;
        $musicSheet->available = $request->cuantity;

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
    public function edit(Request $request)
    {
        $this->rules = array_merge($this->rules, ['title' => ['required', 'unique_with:music_sheets, authorId = author_id,'.$request->id]]);
        $this->validate(
            $request,
            $this->rules,
            $this->messages
        );

        $musicSheet = MusicSheet::find($request->id);
        if ($musicSheet) {
            $musicSheet->title = $request->title;
            $musicSheet->author_id = $request->authorId;
            $musicSheet->gender_id = $request->genderId;
            $musicSheet->cuantity = $request->cuantity;

            $location = Locations::find($request->locationId);
            $location->cabinet_id = $request->cabinetId;
            $location->drawer_id = $request->drawerId;
            $location->save();
            $musicSheet->save();
        }

        return response()->json(['item' => MusicSheet::find($musicSheet->id), 'message' => 'success']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MusicSheet  $musicSheet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $musicSheet = MusicSheet::find($request->id);
        $musicSheet->available -= $request->cuantity;
        $musicSheet->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MusicSheet  $musicSheet
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $musicSheet = MusicSheet::find($id);
        $location = Locations::find($musicSheet->location_id);

        $musicSheet->delete();
        $location->delete();

        return response()->json(['message' => 'success']);
    }
}
