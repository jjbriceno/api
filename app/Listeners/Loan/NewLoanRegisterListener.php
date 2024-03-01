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
        $event->musicSheet->decrement('available', $event->cuantity);

        $event->musicSheet->save();
    }
}
