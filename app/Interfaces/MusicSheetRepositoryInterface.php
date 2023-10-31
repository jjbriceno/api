<?php

namespace App\Interfaces;

use App\Http\Requests\MusicSheetRequest;
use App\Http\Requests\MusicSheetUpdateRequest;

interface MusicSheetRepositoryInterface 
{
    public function store(MusicSheetRequest $request);
    public function edit(MusicSheetUpdateRequest $request);
}
