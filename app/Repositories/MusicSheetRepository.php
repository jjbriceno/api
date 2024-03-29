<?php

namespace App\Repositories;

use App\Models\Author;
use App\Models\Locations;
use App\Models\MusicSheet;
use App\Models\MusicSheetFile;
use App\Http\Resources\MusicSheetResource;
use App\Http\Resources\MusicSheetCollection;
use App\Interfaces\MusicSheetRepositoryInterface;
use App\Http\Requests\MusicSheet\MusicSheetRequest;
use App\Http\Requests\MusicSheet\MusicSheetUpdateRequest;
use App\Models\Gender;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class MusicSheetRepository implements MusicSheetRepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param MusicSheetRequest $request The HTTP request object.
     * @return \Illuminate\Http\JsonResponse The JSON response containing 
     * the newly created resource and a success message.
     */
    public function store(MusicSheetRequest $request)
    {
        try {
            $musicSheet = DB::transaction(function () use ($request) {
                // Se crea una nueva instancia de partitura musical
                $musicSheet = new MusicSheet();
                // Se busca el autor
                $author = Author::findOrFail($request->authorId);
                // Se busca el género musical
                $gender = Gender::find($request->genderId);
                // Se crea una nueva instancia de ubicación
                $location = new Locations();

                if ($request->hasFile('file')) {
                    $title = $author ?
                        $request->title . ' - ' . $author->full_name
                        : $request->file('file')->getClientOriginalName();
                    $file_format = $request->file('file')->getClientOriginalExtension();

                    $musicSheetFile = new MusicSheetFile();
                    $musicSheetFile->file_name = $title;
                    $musicSheetFile->file_format = $file_format;
                    $musicSheetFile->binary_file = base64_encode($request->file('file')->get());
                    $musicSheetFile->save();
                    $musicSheet->music_sheet_file_id = $musicSheetFile->id;
                }

                $musicSheet->title = $request->title;
                $musicSheet->author_id = $author->id;
                $musicSheet->gender_id = $gender->id;
                $musicSheet->quantity = $request->quantity;
                $musicSheet->available = $request->quantity;

                $location->cabinet_id = $request->cabinetId;
                $location->drawer_id = $request->drawerId;
                $location->save();

                $musicSheet->location_id = $location->id;
                $musicSheet->save();

                return $musicSheet;
            });
            return response()->json(['item' => new MusicSheetResource($musicSheet), 'message' => 'success'], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(MusicSheetUpdateRequest $request, $id)
    {
        try {
            $musicSheet = MusicSheet::findOrFail($id);
            DB::transaction(function () use ($request, $musicSheet) {
                // Se busca el autor
                $author = Author::findOrFail($request->authorId);

                // Se busca el género musical
                $gender = Gender::find($request->genderId);
                $musicSheet->title = $request->title;
                $musicSheet->author_id = $author->id;
                $musicSheet->gender_id = $gender->id;

                // Se obtiene la cantidad de partiuras que se han prestado
                $quantity_loaned = $musicSheet->quantity - $musicSheet->available;
                $musicSheet->available = $request->quantity - $quantity_loaned;
                $musicSheet->quantity = $request->quantity;

                if ($request->hasFile('file')) {
                    $title = $author ?
                        $request->title . ' - ' . $author->full_name
                        : $request->file('file')->getClientOriginalName();
                    $file_format = $request->file('file')->getClientOriginalExtension();

                    if ($musicSheet->music_sheet_file_id) {
                        $musicSheetFile = MusicSheetFile::find($musicSheet->music_sheet_file_id);
                    } else {
                        $musicSheetFile = new MusicSheetFile();
                    }

                    if ($musicSheetFile) {
                        $musicSheetFile->fill([
                            'file_name' => $title,
                            'file_format' => $file_format,
                            'binary_file' => base64_encode($request->file('file')->get()),
                        ])->save();

                        $musicSheet->music_sheet_file_id = $musicSheetFile->id;
                    }
                }

                $location = $musicSheet->location()->first();
                $location->cabinet_id = $request->cabinetId;
                $location->drawer_id = $request->drawerId;
                $location->save();
                $musicSheet->save();
                return $musicSheet;
            });

            $musicSheet = MusicSheet::findOrFail($id);

            return new MusicSheetResource($musicSheet);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function index()
    {
        $musicSheets = MusicSheet::filtered()->paginate(10);

        return new MusicSheetCollection($musicSheets);
    }

    public function search()
    {
        if (request('search')) {
            $musicSheet = MusicSheet::search()->paginate(10);
            return new MusicSheetCollection($musicSheet);
        } else {
            return $this->index();
        }
    }

    public function show($id)
    {
        try {
            $musicSheet = MusicSheet::findOrFail($id);
            return response()->json(['item' => new MusicSheetResource($musicSheet), 'message' => 'success'], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_NOT_FOUND);
        } 
    }

    public function destroy($id)
    {
        try{
            $musicSheet = MusicSheet::findOrFail($id);
            // Check if the music sheet is loaned
            if ($musicSheet->loans()->count() > 0) {
                return response()
                    ->json(['errors' => ['deleteError' => ['La partitura no se puede eliminar, debido a que está vinculada a un préstamo.']]],
                    422);
            }
            $musicSheetDeleted = DB::transaction(function () use ($musicSheet) {
                $location = Locations::findOrFail($musicSheet->location_id);
                $musicSheet->delete();
                $location->delete();
                if ($musicSheet->music_sheet_file_id) {
                    $musicSheetFile = MusicSheetFile::findOrFail($musicSheet->music_sheet_file_id);
                    $musicSheetFile->delete();
                }
                return $musicSheet;
            });
            return new MusicSheetResource($musicSheetDeleted);
    
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }
}
