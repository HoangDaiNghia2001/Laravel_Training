<?php

use App\Constants\RouteConstants;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ChildCategoryController;
use App\Http\Controllers\ParentCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// role user
Route::prefix(RouteConstants::ROUTE_USER)->middleware('api.user')->group(function () {
    Route::post('/login', [UserController::class, 'login'])->withoutMiddleware('api.user');
    Route::post('/register', [UserController::class, 'save'])->withoutMiddleware('api.user');
    Route::post('/logout', [UserController::class, 'logout'])->name('user.logout');
    Route::post('/refresh-token', [UserController::class, 'refreshToken'])->name('refresh')->withoutMiddleware('api.user');
    Route::get('/information', [UserController::class, 'getInformation'])->name('user.information');
    Route::put('/information', [UserController::class, 'save'])->name('user.update-information');
});

// role admin
Route::middleware('api.admin')->group(function () {
    //user
    Route::prefix(RouteConstants::ROUTE_USER)->group(function () {
        Route::delete('/trash', [UserController::class, 'moveToTrash'])->name('user.move-to-trash');
        Route::delete('/{id}', [UserController::class, 'delete'])->name('user.delete');
        Route::post('/restoration/{id}', [UserController::class, 'restore'])->name('user.restore');
    });

    // role
    Route::prefix(RouteConstants::ROUTE_ROLE)->group(function () {
        Route::get('/trash', [RoleController::class, 'getAllInTrash'])->name('role.all-in-trash');
        Route::post('', [RoleController::class, 'save'])->name('role.create');
        Route::put('/{id}', [RoleController::class, 'save'])->name('role.update');
        Route::delete('/trash/{id}', [RoleController::class, 'moveToTrash'])->name('role.move-to-trash');
        Route::post('/restoration/{id}', [RoleController::class, 'restore'])->name('role.restore');
    });
    Route::resource(RouteConstants::ROUTE_ROLE, RoleController::class)->only('index', 'destroy');

    // brand
    Route::prefix(RouteConstants::ROUTE_BRAND)->group(function () {
        Route::get('/trash', [BrandController::class, 'getAllInTrash'])->name('brand.get-all-in-trash');
        Route::post('', [BrandController::class, 'save'])->name('brand.create');
        Route::put('/{id}', [BrandController::class, 'save'])->name('brand.update');
        Route::delete('/trash/{id}', [BrandController::class, 'moveToTrash'])->name('brand.move-to-trash');
        Route::delete('/{id}', [BrandController::class, 'destroy'])->name('brand.destroy');
        Route::post('/restoration/{id}', [BrandController::class, 'restore'])->name('brand.restore');
    });

    // Parent Category
    Route::prefix(RouteConstants::ROUTE_PARENT_CATEGORY)->group(function () {
        Route::get('/trash', [ParentCategoryController::class, 'getAllInTrash'])->name('parent-category.get-all-in-trash');
        Route::post('', [ParentCategoryController::class, 'save'])->name('parent-category.create');
        Route::put('/{id}', [ParentCategoryController::class, 'save'])->name('parent-category.update');
        Route::delete('/trash/{id}', [ParentCategoryController::class, 'moveToTrash'])->name('parent-category.move-to-trash');
        Route::delete('/{id}', [ParentCategoryController::class, 'destroy'])->name('parent-category.destroy');
        Route::post('/restoration/{id}', [ParentCategoryController::class, 'restore'])->name('parent-category.restore');
    });

    // Child Category
    Route::prefix(RouteConstants::ROUTE_CHILD_CATEGORY)->group(function () {
        Route::get('/trash', [ChildCategoryController::class, 'getAllInTrash'])->name('child-category.get-all-in-trash');
        Route::post('', [ChildCategoryController::class, 'save'])->name('child-category.create');
        Route::put('/{id}', [ChildCategoryController::class, 'save'])->name('child-category.update');
        Route::delete('/trash/{id}', [ChildCategoryController::class, 'moveToTrash'])->name('child-category.move-to-trash');
        Route::delete('/{id}', [ChildCategoryController::class, 'destroy'])->name('child-category.destroy');
        Route::post('/restoration/{id}', [ChildCategoryController::class, 'restore'])->name('child-category.restore');
    });

    // Product
    Route::prefix(RouteConstants::ROUTE_PRODUCT)->group(function () {
        Route::get('/trash', [ProductController::class, 'getAllInTrash'])->name('product.get-all-in-trash');
        Route::post('', [ProductController::class, 'save'])->name('product.create');
        Route::put('/{id}', [ProductController::class, 'save'])->name('product.update');
        Route::delete('/trash/{id}', [ProductController::class, 'moveToTrash'])->name('product.move-to-trash');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
        Route::post('/restoration/{id}', [ProductController::class, 'restore'])->name('product.restore');
    });
});

// public 
Route::resource(RouteConstants::ROUTE_PRODUCT, ProductController::class)->only('index', 'show');
Route::resource(RouteConstants::ROUTE_CHILD_CATEGORY, ChildCategoryController::class)->only('index', 'show');
Route::resource(RouteConstants::ROUTE_PARENT_CATEGORY, ParentCategoryController::class)->only('index', 'show');
Route::resource(RouteConstants::ROUTE_BRAND, BrandController::class)->only('index', 'show');
