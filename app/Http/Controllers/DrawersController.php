<?php

namespace App\Http\Controllers;

use App\Models\Drawers;

class DrawersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['drawers' => Drawers::all()]);
    }
}
