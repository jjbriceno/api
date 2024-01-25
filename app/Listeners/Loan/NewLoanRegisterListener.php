<?php

namespace App\Listeners\Loan;

use App\Models\MusicSheet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NewLoanRegisterListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        MusicSheet::query()->find($event->musicSheetId)->decrement('available', $event->cuantity);
    }
}
