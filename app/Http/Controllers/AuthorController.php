<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\MusicSheet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\Author\AuthorCollection;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AuthorController extends Controller
{
    use ValidatesRequests;
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
            [
                'fullName' => ['required'],
            ],
            [
                'fullName.required' => 'El nombre es requerido.',
            ]
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
        $this->validate($request, [
            'id' => ['required'],
            'fullName' => ['required']
        ]);

        $author = Author::find($request->id);
        $author->full_name = $request->fullName;
        $author->save();

        return response(['author' => $author->jsonSerialize()], Response::HTTP_CREATED);
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
        $author->full_name = $request->full_name;

        $author->save();

        return response($author, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MusicSheet::where('author_id', $id)->delete();

        Author::destroy($id);

        return response(null, Response::HTTP_OK);
    }
}
