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
               $authorId = Author::find($request->authorId);
               // Se busca el género musical
               $genderId = Author::find($request->authorId);
               // Se crea una nueva instancia de ubicación
               $location = new Locations();
   
               if ($request->hasFile('file')) {
                   $author = Author::find($request->authorId);
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
               $musicSheet->author_id = $request->authorId;
               $musicSheet->gender_id = $request->genderId;
               $musicSheet->cuantity = $request->cuantity;
               $musicSheet->available = $request->cuantity;
   
               $location->cabinet_id = $request->cabinetId;
               $location->drawer_id = $request->drawerId;
               $location->save();
   
               $musicSheet->location_id = $location->id;
               $musicSheet->save();
               
               return $musicSheet;
           });
           return response()->json(['item' => new MusicSheetResource($musicSheet), 'message' => 'success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    // public function store(Request $request)
    // {
    //     // Se validan los datos
    //     $this->validation(
    //         $request,
    //         $this->Rules(),
    //         $this->Messages()
    //     );

    //     $musicSheet = DB::transaction(function () use ($request) {
    //        // Se busca el autor
    //         $authorId = Author::find($request->authorId);
    //         // Se busca el género musical
    //         $genderId = Author::find($request->authorId);
    //         // Se crea una nueva instancia de partitura musical
    //         $musicSheet = new MusicSheet();
    //         // Se crea una nueva instancia de ubicación
    //         $location = new Locations();

    //         // Se almacenan los datos de la partitura
    //         $musicSheet->title = $request->title;
    //         $musicSheet->author_id = $authorId->id;
    //         $musicSheet->gender_id = $genderId->id;
    //         $musicSheet->cuantity = $request->cuantity;
    //         $musicSheet->available = $request->cuantity;


    //         // Se almacenan los datos de la ubicación de la partitura
    //         $location->cabinet_id = $request->cabinetId;
    //         $location->drawer_id = $request->drawerId;
    //         $location->save();

    //         // Se almacena el ID de la ubicación
    //         $musicSheet->location_id = $location->id;
    //         // Se guarda la partitura
    //         $musicSheet->save();

    //         return $musicSheet;
    //     });

    //     return response()->json([
    //         'item' => $musicSheet,
    //         'message' => 'success']
    //         , 200); 
    // }

    public function update(MusicSheetUpdateRequest $request, MusicSheet $musicSheet)
    {
        if ($musicSheet) {
            $musicSheet->title = $request->title;
            $musicSheet->author_id = $request->authorId;
            $musicSheet->gender_id = $request->genderId;
            $musicSheet->cuantity = $request->cuantity;

            if ($request->hasFile('file')) {
                $author = Author::find($request->authorId);
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
        }

        return response()->json(['item' => new MusicSheetResource($musicSheet), 'message' => 'success']);
    }

    public function index()
    {
        $musicSheets = MusicSheet::filtered()->paginate(10);

        return new MusicSheetCollection($musicSheets);
    }

    public function search()
    {
        if(request('search')) {
            $musicSheet = MusicSheet::search()->paginate(10);
            return new MusicSheetCollection($musicSheet);
        } else {
            return $this->index();
        }
    }
}
