<?php

namespace App\Http\Resources\Calendar;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CalendarResourceCollection extends ResourceCollection
{
    /**
     * The "data" wrapper that should be applied.
     */
    public static $wrap = 'events';
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): AnonymousResourceCollection
    {
        return CalendarResource::collection($this->collection);
    }
}
