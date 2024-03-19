<?php

namespace App\Http\Controllers;

use App\Models\Locations;
use App\Models\MusicSheet;
use Illuminate\Http\Request;
use App\Models\MusicSheetFile;
use App\Http\Requests\MusicSheet\MusicSheetRequest;
use App\Http\Requests\MusicSheet\MusicSheetUpdateRequest;
use App\Http\Resources\MusicSheetCollection;
use App\Http\Resources\MusicSheetResource;
use App\Interfaces\MusicSheetRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class MusicSheetController extends Controller
{
    private MusicSheetRepositoryInterface $musicSheetRepository;

    public function __construct(MusicSheetRepositoryInterface $musicSheetRepository)
    {
        $this->musicSheetRepository = $musicSheetRepository;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MusicSheetRequest $request)
    {
        return $this->musicSheetRepository->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MusicSheet  $musicSheet
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $musicSheet = MusicSheet::findOrFail($id);
            return new MusicSheetResource($musicSheet);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MusicSheet  $musicSheet
     * @return \Illuminate\Http\Response
     */
    public function edit()
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
    public function update(MusicSheetUpdateRequest $request, $id)
    {
        return $this->musicSheetRepository->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MusicSheet  $musicSheet
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->musicSheetRepository->destroy($id);
    }

    public function index()
    {
        return $this->musicSheetRepository->index();
    }
    
    public function search()
    {
        return $this->musicSheetRepository->search();
    }
}
