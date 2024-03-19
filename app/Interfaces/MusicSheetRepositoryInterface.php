<?php

namespace App\Interfaces;

use App\Http\Requests\MusicSheet\MusicSheetRequest;
use App\Http\Requests\MusicSheet\MusicSheetUpdateRequest;
use App\Models\MusicSheet;

interface MusicSheetRepositoryInterface
{
    public function store(MusicSheetRequest $request);
    public function update(MusicSheetUpdateRequest $request, $id);
    public function index();
    public function search();
    public function show($id);
    public function destroy($id);
}
