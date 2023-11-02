<?php

namespace App\Repositories;

use App\Models\Author;
use App\Models\Locations;
use App\Models\MusicSheet;
use App\Models\MusicSheetFile;
use App\Http\Requests\MusicSheet\MusicSheetRequest;
use App\Http\Resources\MusicSheetResource;
use App\Http\Requests\MusicSheet\MusicSheetUpdateRequest;
use App\Interfaces\MusicSheetRepositoryInterface;

class MusicSheetRepository implements MusicSheetRepositoryInterface
{
    public function store(MusicSheetRequest $request)
    {
        $musicSheet = new MusicSheet();
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

        return response()->json(['item' => $musicSheet, 'message' => 'success'], 200);
    }

    public function edit(MusicSheetUpdateRequest $request)
    {
        $musicSheet = MusicSheet::find($request->id);
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

            $location = Locations::find($request->locationId);
            $location->cabinet_id = $request->cabinetId;
            $location->drawer_id = $request->drawerId;
            $location->save();
            $musicSheet->save();
        }

        return response()->json(['item' => new MusicSheetResource($musicSheet), 'message' => 'success']);
    }
}
