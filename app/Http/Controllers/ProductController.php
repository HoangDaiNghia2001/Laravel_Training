<?php

namespace App\Http\Controllers;

use App\Constants\RouteConstants;
use App\Http\Requests\ProductRequest as ProRequest;
use App\Request\ProductRequest;
use App\Services\ProductService;
use App\Traits\MethodTrait;
use Illuminate\Http\Request;

class ProductController extends Controller {
    protected $productService;

    use MethodTrait;

    public function __construct(ProductService $productService) {
        $this->productService = $productService;
    }

    //SUCCESS
    public function index(Request $request) {
        $name = $request->query('name');
        $brandId = $request->query('brandId');
        $parentCategoryId = $request->query('parentCategoryId');
        $childCategoryId = $request->query('childCategoryId');
        $color = $request->query('color');
        $minPrice = $request->query('minPrice');
        $maxPrice = $request->query('maxPrice');
        $sortBy = $request->query('sortBy');

        return $this->productService->filter($name, $brandId, $parentCategoryId, $childCategoryId, $color, $minPrice, $maxPrice, $sortBy);
    }

    //SUCCESS
    public function getAllInTrash() {
        return $this->productService->getAllInTrash();
    }

    //SUCCESS
    public function show(Request $request) {
        $id = RouteConstants::ROUTE_PRODUCT;
        return $this->productService->getById($request->$id);
    }

    //SUCCESS
    public function save(ProRequest $request) {
        $mainImage = '';
        if ($request->hasFile('mainImage')) {
            $mainImage = $request->getSchemeAndHttpHost() . '/assets/img/' . time() . '.'  . $request->mainImage->extension();

            $request->mainImage->move(public_path('/assets/img/'), $mainImage);
        }

        $productData = $request->only([
            'name', 'description', 'title', 'price', 'discountedPercent',
            'quantity', 'color', 'brandId', 'parentCategoryId', 'childCategoryId'
        ]);

        $productData['mainImage'] = $mainImage;

        $id = $request->id;
        if (empty($id)) {
            return $this->productService->create($this->SnakeCase($productData));
        } else {
            return $this->productService->update($id, $this->SnakeCase($productData));
        }
    }

    //SUCCESS
    public function moveToTrash($id) {
        return $this->productService->moveToTrash((int)$id);
    }

    //SUCCESS
    public function destroy($id) {
        return $this->productService->delete((int)$id);
    }

    //SUCCESS
    public function restore($id) {
        return $this->productService->restore((int)$id);
    }
}
