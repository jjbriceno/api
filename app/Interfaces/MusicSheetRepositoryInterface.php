<?php

namespace App\Interfaces;

use App\Http\Requests\MusicSheet\MusicSheetRequest;
use App\Http\Requests\MusicSheet\MusicSheetUpdateRequest;

interface MusicSheetRepositoryInterface
{
    public function store(MusicSheetRequest $request);
    public function edit(MusicSheetUpdateRequest $request);
    public function index();
    public function search();
}
