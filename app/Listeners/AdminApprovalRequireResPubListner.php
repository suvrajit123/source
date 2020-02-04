<?php

namespace App\Listeners;

use App\Events\AdminApprovalRequireResPubEvent;
use App\Mail\AdminApprovalRequireResPubMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class AdminApprovalRequireResPubListner
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
     * @param  UserRegisteredEvent  $event
     * @return void
     */
    public function handle(AdminApprovalRequireResPubEvent $event)
    {
        Mail::to('kalyan.cmc@gmail.com')->send(new AdminApprovalRequireResPubMail($event->resource, $event->user));
    }
}
