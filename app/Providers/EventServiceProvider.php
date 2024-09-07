<?php

namespace App\Providers;

use App\Events\CreateNewProduct;
use App\Events\DeleteUserEvent;
use App\Events\RestoreUserEvent;
use App\Events\SoftDeleteUserEvent;
use App\Events\UpdateUserEvent;
use App\Listeners\DeleteUserListener;
use App\Listeners\RestoreUserListener;
use App\Listeners\SendEmailNotifiction;
use App\Listeners\SoftDeleteUserListener;
use App\Listeners\UpdateProductListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider {
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CreateNewProduct::class => [
            SendEmailNotifiction::class
        ],
        SoftDeleteUserEvent::class => [
            SoftDeleteUserListener::class
        ],
        DeleteUserEvent::class => [
            DeleteUserListener::class
        ],
        RestoreUserEvent::class => [
            RestoreUserListener::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool {
        return false;
    }
}
