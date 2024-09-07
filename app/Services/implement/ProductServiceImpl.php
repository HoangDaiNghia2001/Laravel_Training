<?php

namespace App\Services\implement;

use App\Events\CreateNewProduct;
use App\Exceptions\CustomException;
use App\Models\Brand;
use App\Models\ChildCategory;
use App\Models\ParentCategory;
use App\Repositories\BrandRepositoryInterface;
use App\Repositories\ChildCategoryRepositoryInterface;
use App\Repositories\ParentCategoryRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Services\ProductService;
use App\Traits\MethodTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductServiceImpl implements ProductService {

    use MethodTrait;

    protected $productRepostitory;
    protected $brandRepository;
    protected $parentCategoryRepository;
    protected $childCategoryRepository;

    public function __construct(
        ProductRepositoryInterface $productRepostitory,
        BrandRepositoryInterface $brandRepository,
        ParentCategoryRepositoryInterface $parentCategoryRepository,
        ChildCategoryRepositoryInterface $childCategoryRepository,
    ) {
        $this->productRepostitory = $productRepostitory;
        $this->brandRepository = $brandRepository;
        $this->parentCategoryRepository = $parentCategoryRepository;
        $this->childCategoryRepository = $childCategoryRepository;
    }

    //SUCCESS
    public function filter(?string $name, ?int $brandId, ?int $parentCategoryId, ?int $childCategoryId, ?string $color, ?float $minPrice, ?float $maxPrice, ?string $sortBy): JsonResponse {
        $products = $this->productRepostitory->filter($name, $brandId, $parentCategoryId, $childCategoryId, $color, $minPrice, $maxPrice, $sortBy);
        $message = 'Get all products in database success !!!';
        $data = [
            'results' => $products,
            'total' => count($products)
        ];

        return $this->buildApiResponse(true, $message, $data, Response::HTTP_OK);
    }

    //SUCCESS
    public function getAllInTrash(): JsonResponse {
        $products = $this->productRepostitory->getAllInTrash();
        $message = 'Get all products in trash success !!!';
        $data = [
            'results' => $products,
            'total' => count($products)
        ];

        return $this->buildApiResponse(true, $message, $data, Response::HTTP_OK);
    }

    //SUCCESS
    public function getById(int $id): JsonResponse {
        try {
            $product = $this->productRepostitory->getById($id);

            $message = 'Get product detail success !!!';
            return $this->buildApiResponse(true, $message, $product, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->buildErrorResponse($e->getMessage(), $e->getCode());
        }
    }

    //SUCCESS
    private function validateParentCategory(int $parentCategoryId, int $brandId): ParentCategory {
        $parentCategory = $this->parentCategoryRepository->getById($parentCategoryId);
        if ($parentCategory->brand_id != $brandId) {
            throw new CustomException('Parent category not found with id: ' . $parentCategoryId . ' in the specified brand !!!', Response::HTTP_NOT_FOUND);
        }
        return $parentCategory;
    }

    //SUCCESS
    private function validateChildCategory(int $childCategoryId, int $parentCategoryId): ChildCategory {
        $childCategory = $this->childCategoryRepository->getById($childCategoryId);
        if ($childCategory->parent_category_id != $parentCategoryId) {
            throw new CustomException('Child category not found with id: ' . $childCategoryId . ' in the specified parent category !!!', Response::HTTP_NOT_FOUND);
        }
        return $childCategory;
    }

    //SUCCESS
    protected function prepareProductData(array $data, Brand $brand, ParentCategory $parentCategory,ChildCategory $childCategory): array
    {
        return array_merge($data, [
            'brand_name' => $brand->name,
            'parent_category_name' => $parentCategory->name,
            'child_category_name' => $childCategory->name,
            'discounted_price' => $data['price'] - ($data['price'] * ($data['discounted_percent'] / 100)),
        ]);
    }

    //SUCCESS
    public function create(array $data): JsonResponse {
        return $this->handleTransaction(function () use ($data) {
            // DK 1 => kiểm tra xem brand id nhập vào có tồn tại không
            // DK 2 => kiểm tra xem parent category id nhập vào có tồn tại không 
            //         và có brand_id trùng với id của brand vừa kiểm tra không
            // DK 3 => kiểm tra xem child category id nhập vào có tồn tại không
            //          và có parent_category_id trùng với id của parent category vừa kiểm tra không
            $brand = $this->brandRepository->getById($data['brand_id']);
            $parentCategory = $this->validateParentCategory($data['parent_category_id'], $brand->id);
            $childCategory = $this->validateChildCategory($data['child_category_id'], $parentCategory->id);

            $productData = $this->prepareProductData($data, $brand, $parentCategory, $childCategory);
            $productData['created_by'] = auth()->user()->id;
            $productData['updated_by'] = auth()->user()->id;
            $product = $this->productRepostitory->create($productData);
            event(new CreateNewProduct($product));
            return $this->buildApiResponse(true, 'Created product success !!!', $product, Response::HTTP_CREATED);
        });
    }

    //SUCCESS
    public function update(int $id, array $data): JsonResponse {
        return $this->handleTransaction(function () use ($id, $data) {
            $brand = $this->brandRepository->getById($data['brand_id']);
            $parentCategory = $this->validateParentCategory($data['parent_category_id'], $brand->id);
            $childCategory = $this->validateChildCategory($data['child_category_id'], $parentCategory->id);

            $productData = $this->prepareProductData($data, $brand, $parentCategory, $childCategory);
            $productData['updated_by'] = auth()->user()->id;
            $product = $this->productRepostitory->update($id, $productData);
            return $this->buildApiResponse(true, 'Update product success !!!', $product, Response::HTTP_CREATED);
        });
    }

    //SUCCESS
    public function moveToTrash(int $id): JsonResponse {
        return $this->handleTransaction(function () use ($id) {
            $this->productRepostitory->moveToTrash($id);
            $message = 'Move product to trash success !!!';
            return $this->buildApiResponse(true, $message, null, Response::HTTP_OK);
        });
    }

    //SUCCESS
    public function delete(int $id): JsonResponse {
        return $this->handleTransaction(function () use ($id) {
            $this->productRepostitory->delete($id);
            $message = 'Delete product success !!!';
            return $this->buildApiResponse(true, $message, null, Response::HTTP_OK);
        });
    }

    //SUCCESS
    public function restore(int $id): JsonResponse {
        return $this->handleTransaction(function () use ($id) {
            $this->productRepostitory->restore($id);
            $message = 'Restore product success !!!';
            return $this->buildApiResponse(true, $message, null, Response::HTTP_OK);
        });
    }
}
