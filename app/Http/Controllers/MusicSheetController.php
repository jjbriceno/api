<?php

namespace App\Http\Controllers;

use App\Models\Locations;
use App\Models\MusicSheet;
use Illuminate\Http\Request;
use App\Models\MusicSheetFile;
use App\Http\Requests\MusicSheet\MusicSheetRequest;
use App\Http\Resources\MusicSheetCollection;
use App\Http\Requests\MusicSheet\MusicSheetUpdateRequest;
use App\Interfaces\MusicSheetRepositoryInterface;

class MusicSheetController extends Controller
{
    private MusicSheetRepositoryInterface $musicSheetRepository;

    public function __construct(MusicSheetRepositoryInterface $musicSheetRepository)
    {
        $this->musicSheetRepository = $musicSheetRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     // $musicSheets = MusicSheet::query()->where('music_sheet_file_id', '!=', 0);
    //     $musicSheets = MusicSheet::query()->get();

    //     return new MusicSheetCollection($musicSheets);
    // }

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
    public function store(MusicSheetRequest $request)
    {
        $this->musicSheetRepository->store($request);
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
    public function edit(MusicSheetUpdateRequest $request)
    {
        $this->musicSheetRepository->edit($request);
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
        if ($musicSheet->music_sheet_file_id) {
            $musicSheetFile = MusicSheetFile::find($musicSheet->music_sheet_file_id);
            $musicSheetFile->delete();
        }

        $musicSheet->delete();
        $location->delete();

        return response()->json(['message' => 'success']);
    }

    public function index()
    {
        return $this->musicSheetRepository->index();
    }
}
