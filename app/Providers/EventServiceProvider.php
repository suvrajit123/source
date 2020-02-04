<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \App\Events\ContactUsEvent::class => [
            \App\Listeners\SendContactUsEmailAdminListner::class
        ],
        \App\Events\UserActivationEvent::class => [
            \App\Listeners\UserActivationListner::class
        ],
        \App\Events\ResourceBanEvent::class => [
            \App\Listeners\ResourceBanListner::class
        ],
        \App\Events\AdminUserContactEvent::class => [
            \App\Listeners\AdminUserContactListner::class
        ],
        \App\Events\AdminApprovalRequireResPubEvent::class => [
            \App\Listeners\AdminApprovalRequireResPubListner::class
        ],
        \App\Events\ForgotPasswordEvent::class => [
            \App\Listeners\ForgotPasswordListner::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
