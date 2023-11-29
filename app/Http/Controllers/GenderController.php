<?php

namespace App\Http\Controllers;

use App\Models\Gender;
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
            'genderName' => ['required']
        ],
        [
            'genderName.required' => 'El nombre del género es requerido'
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
        $this->validate($request, [
            'genderName' => ['required']
        ]);

        $gender = Gender::find($request->id);
        $gender->name = $request->genderName;
        $gender->save();

        return response(['gender' => $gender->jsonSerialize()], Response::HTTP_CREATED);
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
        $gender->name = $request->name;

        $gender->save();

        return response($gender, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gender::destroy($id);

        return response(null, Response::HTTP_OK);
    }
}
