<?php

namespace App\Http\Resources\MusicSheet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanMusicSheetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author->full_name,
            'quantity' => $this->pivot->quantity,
            'music_sheet_file_id' => $this->music_sheet_file_id
        ];
    }
}
