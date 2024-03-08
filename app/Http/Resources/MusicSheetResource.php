<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MusicSheetResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'music_sheet';
    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                        => $this->id,
            'title'                     => $this->title,
            'available'                 => $this->available,
            'quantity'                  => $this->quantity,
            'author'                    => $this->author,
            'gender'                    => $this->gender,
            'location'                  => $this->location,
            'music_sheet_file_id'       => $this->music_sheet_file_id ?? $this->music_sheet_file_id
        ];
    }
}
