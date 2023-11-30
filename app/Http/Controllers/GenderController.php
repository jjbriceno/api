<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use App\Models\MusicSheet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['genders' => Gender::all()]);
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
        $this->validate($request, [
            'genderName' => ['required', 'unique:genders,name']
        ],
        [
            'genderName.required' => 'El nombre del género es requerido',
            'genderName.unique' => 'El nombre del género ya ha sido registrado'
        ]
    );

        $gender = new Gender();
        $gender->name = $request->genderName;
        $gender->save();

        return response(['gender' => $gender->jsonSerialize()], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function show(Gender $gender)
    {
        return response(['gender' => $gender->jsonSerialize()], Response::HTTP_OK);
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
    public function update(Request $request, Gender $gender)
    {
        $this->validate($request, [
            'genderName' => ['required', 'unique:genders,name,' . $gender->id]
        ],
        [
            'genderName.required' => 'El nombre del género es requerido',
            'genderName.unique' => 'El nombre del género ya ha sido registrado'
        ]);

        $gender->name = $request->genderName;
        $gender->save();

        return response(['gender' => $gender->jsonSerialize()], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            MusicSheet::where('gender_id', $id)->delete();
            $gender = Gender::find($id);
            $gender->delete();
    
            return response()->json(['gender' => $gender->jsonSerialize(), 'message' => 'success'], Response::HTTP_OK);
    
        } catch (\Throwable $th) {
            return response()->json(['message' => "Ocurrió un error durante la eliminación"], 500);
        }
    }
}
