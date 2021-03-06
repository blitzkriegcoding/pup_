<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AddInterestQuoteEvent
{
    use InteractsWithSockets, SerializesModels;
    /**
    *
    *@var private generated quote id
    */
    public $id_quote;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id_quote)
    {
        //
         $this->id_quote = $id_quote;
    }

    public function getIdQuote()
    {
        return $this->id_quote;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return [];
    }
}
