<?php

namespace App\Events\Loans;

use App\Models\Loans;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewLoanRegisterEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $musicSheetId;

    public $cuantity;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($musicSheetId, $cuantity)
    {
        $this->musicSheetId = $musicSheetId;
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
