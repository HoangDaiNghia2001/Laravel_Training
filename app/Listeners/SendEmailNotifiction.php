<?php

namespace App\Listeners;

use App\Events\CreateNewProduct;
use App\Mail\EmailNotification;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailNotifiction {
    /**
     * Create the event listener.
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CreateNewProduct $event): void {
        $product = $event->product;
        Mail::to(auth()->user()->email)
            ->send(new EmailNotification($product));
    }
}
