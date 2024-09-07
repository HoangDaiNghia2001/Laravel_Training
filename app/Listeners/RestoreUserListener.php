<?php

namespace App\Listeners;

use App\Constants\RoleConstants;
use App\Events\RestoreUserEvent;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RestoreUserListener {
    /**
     * Create the event listener.
     */
    protected $productRepository;
    public function __construct(ProductRepositoryInterface $productRepository) {
        $this->productRepository = $productRepository;
    }

    /**
     * Handle the event.
     */
    public function handle(RestoreUserEvent $event): void {
        $user = $event->user;

        $admin =  $user->roles()->where('name', RoleConstants::ADMIN_ROLE)->exists();

        if ($admin) {
            $products = $this->productRepository->getByIdUserInTrash($user->id);

            foreach ($products as $product) {
                $product->restore();
            }
        }
    }
}
