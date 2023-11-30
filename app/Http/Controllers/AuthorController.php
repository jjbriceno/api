<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\MusicSheet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\Author\AuthorCollection;
use Exception;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AuthorController extends Controller
{
    use ValidatesRequests;
    protected array $rules;
    protected array $messages;

    private function Rules($id = '') 
    {
        return $id ?
        [
            'fullName' => ['required', 'unique:authors,full_name,' . $id],
        ]
        : [
            'fullName' => ['required', 'unique:authors,full_name'],
        ];
    }

    private function Messages()
    {
        return [
            'fullName.required' => 'El nombre de autor es requerido.',
            'fullName.unique'   => 'El nombre de autor ya ha sido registrado.',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $musicSheets = MusicSheet::filtered()->paginate(10);

        // return new MusicSheetCollection($musicSheets);
        // return response()->json(['authors' => Author::all()]);
        $authors = Author::filtered()->paginate(10);
        return new AuthorCollection($authors);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
            $this->Rules(),
            $this->Messages()
        );

        $author = new Author();

        $author->full_name = $request->fullName;

        $author->save();

        return response(['author' => $author->jsonSerialize()], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        return response(['author' => $author->jsonSerialize()], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Author $author)
    {
        $this->validate(
            $request,
            $this->Rules($author->id),
            $this->Messages()
        );

        $author = Author::find($request->id);
        $author->full_name = $request->fullName;
        $author->save();

        return response(['author' => $author->jsonSerialize()], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            MusicSheet::where('author_id', $id)->delete();
            $author = Author::find($id);
            $author->delete();
    
            return response()->json(['author' => $author->jsonSerialize(), 'message' => 'success'], Response::HTTP_OK);
    
        } catch (\Throwable $th) {
            return response()->json(['message' => "Ocurrió un error durante la eliminación"], 500);
        }
    }
}
