<?php

namespace App\Http\Controllers;

use App\Models\MusicSheetFile;
use Illuminate\Http\Request;

class MusicSheetFileController extends Controller
{
    protected array $rules;
    protected array $messages;

    public function __construct()
    {
        $this->rules = [
            'musicSheetFile'    => 'sometimes|required|mimes:jpeg,png,pdf|max:2048',
        ];

        $this->messages = [
            'musicSheetFile.required'       => "El Archivo es obligatorio",
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

        if ($request->hasFile('musicSheetFile')) {
            $musicSheetFile = MusicSheetFile::create([
                'file_name' => $request->file('musicSheetFile')->getClientOriginalName(),
                'file_format' => $request->file('musicSheetFile')->getClientOriginalExtension(),
                'binary_file' => base64_encode($request->file('musicSheetFile')->get()),
            ]);
        }
        return response()->json(['record' => $musicSheetFile, 'message' => 'success'], 200);
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
    public function update(Request $request, musicSheetFile $musicSheetFile)
    {
        $this->validate(
            $request,
            $this->rules,
            $this->messages
        );

        if ($request->hasFile('musicSheetFile')) {
            if ($musicSheetFile) {
                $musicSheetFile->file_name = $request->file('musicSheetFile')->getClientOriginalName();
                $musicSheetFile->file_format = $request->file('musicSheetFile')->getClientOriginalExtension();
                $musicSheetFile->binary_file = base64_encode(file_get_contents($request->file('musicSheetFile')->getRealPath()));
                $musicSheetFile->save();
            }
        }

        return response()->json(['message' => 'success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\musicSheetFile  $musicSheetFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(musicSheetFile $musicSheetFile)
    {
        $musicSheetFile->delete();

        return response()->json(['message' => 'success']);
    }

    public function download(MusicSheetFile $musicSheetFile)
    {
        if ($musicSheetFile) {
            // Lee el contenido del recurso de transmisión en una variable
            $stream_get_contents = stream_get_contents($musicSheetFile->binary_file);
            if ($stream_get_contents !== false) {
                $fileContent = base64_decode($stream_get_contents);
                $headers = [
                    'Content-Type' => 'application/octet-stream',
                    'Content-Disposition' => 'attachment; filename="' . $musicSheetFile->file_name . '"',
                ];
                return response($fileContent, 200, $headers);
            } else {
                return response()->json(['message' => 'Hubo un error en la descarga'], 404);
            }
        } else {
            return response()->json(['message' => 'Archivo no encontrado'], 404);
        }
    }
}
