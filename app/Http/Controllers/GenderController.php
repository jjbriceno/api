<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use App\Models\MusicSheet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\Gender\GenderCollection;

class GenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $genders = Gender::filtered()->paginate(10);

        return new GenderCollection($genders);
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
            [
                'genderName' => ['required', 'unique:genders,name']
            ],
            [
                'genderName.required' => 'El nombre del género es requerido',
                'genderName.unique' => 'El nombre del género ya ha sido registrado'
            ]
        );

        try {
            $gender = Gender::create([
                'name' => $request->genderName
            ]);
            return response(['gender' => $gender->jsonSerialize()], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $gender = Gender::findOrFail($id);
            return response(['gender' => $gender->jsonSerialize()], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Gender  $gender
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
     * @param  \App\Models\Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'genderName' => ['required', 'unique:genders,name,' . $id]
            ],
            [
                'genderName.required' => 'El nombre del género es requerido',
                'genderName.unique' => 'El nombre del género ya ha sido registrado'
            ]
        );

        try {
            $gender = Gender::findOrFail($id);
            $gender->name = $request->genderName;
            $gender->save();

            return response(['gender' => $gender->jsonSerialize()], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            MusicSheet::where('gender_id', $id)->delete();
            $gender = Gender::findOrFail($id);
            $gender->delete();

            return response()->json(['gender' => $gender->jsonSerialize(), 'message' => 'success'], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function search(Request $request)
    {
        if (request('search')) {
            $genders = Gender::search()->paginate(10);
            return new GenderCollection($genders);
        } else {
            return $this->index();
        }
    }
}
