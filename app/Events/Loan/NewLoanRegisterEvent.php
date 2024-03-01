<?php

namespace App\Events\Loan;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class NewLoanRegisterEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $musicSheet;

    public $cuantity;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($musicSheet, $cuantity)
    {
        $this->musicSheet = $musicSheet;
        
        $this->cuantity = $cuantity;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
