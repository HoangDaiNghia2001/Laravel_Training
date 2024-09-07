<?php

namespace App\Listeners;

use App\Constants\RoleConstants;
use App\Events\DeleteUserEvent;
use App\Repositories\ProductRepositoryInterface;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class DeleteUserListener {
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
    public function handle(DeleteUserEvent $event): void {
        $user = $event->user;

        $admin =  $user->roles()->where('name', RoleConstants::ADMIN_ROLE)->exists();

        if ($admin) {
            $products = $this->productRepository->getByIdUserInTrash($user->id);

            // dd(count($products));
            foreach ($products as $product) {
                $product->forceDelete();
            }
        }
    }
}
