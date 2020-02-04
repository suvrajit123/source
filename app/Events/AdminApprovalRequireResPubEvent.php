<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Request;
use App\User;

class AdminApprovalRequireResPubEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $resource;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $resource)
    {
        $this->user = $user;
        $this->resource = $resource;
    }
}
