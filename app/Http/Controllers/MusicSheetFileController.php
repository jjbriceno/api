<?php

namespace App\Http\Controllers;

use App\Models\MusicSheetFile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class MusicSheetFileController extends Controller
{
    protected array $rules;
    protected array $messages;

    public function __construct()
    {
        $this->rules = [
            'musicSheetFile'    => 'sometimes|mimes:jpeg,png,pdf|max:2048',
        ];

        $this->messages = [
            'musicSheetFile.mimes'          => "Sólo se aceptan los formatos de archivo jpeg, png o pdf",
            'musicSheetFile.required'       => "El tamaño maximo del archivo es de 2 MB",
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            $this->rules,
            $this->messages
        );
        try {
            if ($request->hasFile('musicSheetFile')) {
                $musicSheetFile = MusicSheetFile::create([
                    'file_name' => $request->file('musicSheetFile')->getClientOriginalName(),
                    'file_format' => $request->file('musicSheetFile')->getClientOriginalExtension(),
                    'binary_file' => base64_encode($request->file('musicSheetFile')->get()),
                ]);
            }
            return response()->json(['message' => 'success'], Response::HTTP_CREATED);

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\musicSheetFile  $musicSheetFile
     * @return \Illuminate\Http\Response
     */
    public function show(musicSheetFile $musicSheetFile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\musicSheetFile  $musicSheetFile
     * @return \Illuminate\Http\Response
     */
    public function edit(musicSheetFile $musicSheetFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\musicSheetFile  $musicSheetFile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            $this->rules,
            $this->messages
        );

        try {
            $musicSheetFile = MusicSheetFile::findOrFail($id);

            if ($request->hasFile('musicSheetFile')) {
                if ($musicSheetFile) {
                    $musicSheetFile->file_name = $request->file('musicSheetFile')->getClientOriginalName();
                    $musicSheetFile->file_format = $request->file('musicSheetFile')->getClientOriginalExtension();
                    $musicSheetFile->binary_file = base64_encode(file_get_contents($request->file('musicSheetFile')->getRealPath()));
                    $musicSheetFile->save();
                }
            }
            return response()->json(['message' => 'success'], Response::HTTP_OK);

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\musicSheetFile  $musicSheetFile
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $musicSheetFile = MusicSheetFile::findOrFail($id);
            $musicSheetFile->delete();
            return response()->json(['message' => 'success'], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
            
    }

    public function download($id)
    {
        try {
            $musicSheetFile = MusicSheetFile::query()->findOrFail($id);
            // Lee el contenido del recurso de transmisión en una variable
            $stream_get_contents = stream_get_contents($musicSheetFile->binary_file);
            if ($stream_get_contents !== false) {
                $path       = public_path($musicSheetFile->file_name);
                $contents   = base64_decode($stream_get_contents);
                //store file temporarily
                file_put_contents($path, $contents);
                $response = response()->download($path, $musicSheetFile->file_name)->deleteFileAfterSend(true);
                $response->headers->set('Access-Control-Expose-Headers', 'Content-Disposition');
                return $response;
            } else {
                new \Exception("Error Processing Request");
            }
        
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_NOT_FOUND);
        } 
    }
}
