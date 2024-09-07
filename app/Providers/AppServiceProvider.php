<?php

namespace App\Providers;

use App\Http\Controllers\BrandController;
use App\Models\Brand;
use App\Models\ChildCategory;
use App\Models\ParentCategory;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use App\Repositories\BrandRepositoryInterface;
use App\Repositories\ChildCategoryRepositoryInterface;
use App\Repositories\implement\BrandRepository;
use App\Repositories\implement\ChildCategoryRepository;
use App\Repositories\implement\ParentCategoryRepository;
use App\Repositories\implement\ProductRepository;
use App\Repositories\implement\RoleRepository;
use App\Repositories\implement\UserRepository;
use App\Repositories\ParentCategoryRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\RepositoryInterface;
use App\Repositories\RoleRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\BrandService;
use App\Services\ChildCategoryService;
use App\Services\implement\BrandServiceImpl;
use App\Services\implement\ChildCategoryServiceImpl;
use App\Services\implement\ParentCategoryServiceImpl;
use App\Services\implement\ProductServiceImpl;
use App\Services\implement\RoleServiceImpl;
use App\Services\implement\UserServiceImpl;
use App\Services\ParentCategoryService;
use App\Services\ProductService;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        $this->bindServiceAndRepository(
            UserServiceImpl::class,
            UserService::class,
            UserRepositoryInterface::class,
            UserRepository::class,
            User::class
        );
        $this->bindServiceAndRepository(
            RoleServiceImpl::class,
            RoleService::class,
            RoleRepositoryInterface::class,
            RoleRepository::class,
            Role::class
        );
        $this->bindServiceAndRepository(
            BrandServiceImpl::class,
            BrandService::class,
            BrandRepositoryInterface::class,
            BrandRepository::class,
            Brand::class
        );
        $this->bindServiceAndRepository(
            ParentCategoryServiceImpl::class,
            ParentCategoryService::class,
            ParentCategoryRepositoryInterface::class,
            ParentCategoryRepository::class,
            ParentCategory::class
        );
        $this->bindServiceAndRepository(
            ChildCategoryServiceImpl::class,
            ChildCategoryService::class,
            ChildCategoryRepositoryInterface::class,
            ChildCategoryRepository::class,
            ChildCategory::class
        );
        $this->bindServiceAndRepository(
            ProductServiceImpl::class,
            ProductService::class,
            ProductRepositoryInterface::class,
            ProductRepository::class,
            Product::class
        );
    }

    private function bindServiceAndRepository(
        string $serviceImplClass,
        string $serviceInterface,
        string $repositoryInterface,
        string $repositoryClass,
        string $modelClass
    ): void {

        $this->app->bind($repositoryInterface, function () use ($repositoryClass, $modelClass) {
            return new $repositoryClass(new $modelClass);
        });

        $this->app->bind($serviceInterface, $serviceImplClass);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
    }
}
