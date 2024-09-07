<?php

namespace App\Listeners;

use App\Constants\RoleConstants;
use App\Events\SoftDeleteUserEvent;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use PHPUnit\TextUI\Configuration\Constant;

class SoftDeleteUserListener {
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
    public function handle(SoftDeleteUserEvent $event): void {
        $user = $event->user;

        $admin =  $user->roles()->where('name', RoleConstants::ADMIN_ROLE)->exists();

        if ($admin) {
            $products = $this->productRepository->getByIdUser($user->id);

            foreach ($products as $product) {
                $product->delete();
            }
        }
    }
}
