<?php

namespace App\Services\implement;

use App\Exceptions\CustomException;
use App\Models\Brand;
use App\Repositories\BrandRepositoryInterface;
use App\Repositories\ParentCategoryRepositoryInterface;
use App\Services\ParentCategoryService;
use App\Traits\MethodTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ParentCategoryServiceImpl implements ParentCategoryService {

    use MethodTrait;

    protected $parentCategoryRepository;

    protected $brandRepository;

    public function __construct(
        ParentCategoryRepositoryInterface $parentCategoryRepository,
        BrandRepositoryInterface $brandRepository
    ) {
        $this->parentCategoryRepository = $parentCategoryRepository;
        $this->brandRepository = $brandRepository;
    }

    //SUCCESS
    public function getByBrand(int $brandId): JsonResponse {
        try {
            $parentCategories = $this->parentCategoryRepository->getByBrand($brandId);
            $message = 'Get parent categories of brand: ' . $brandId . ' in database success !!!';
            $data = [
                'results' => $parentCategories,
                'total' => count($parentCategories)
            ];

            return $this->buildApiResponse(true, $message, $data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->buildErrorResponse($e->getMessage(), $e->getCode());
        }
    }

    //SUCCESS
    public function getAllInTrash(): JsonResponse {
        $parentCategories = $this->parentCategoryRepository->getAllInTrash();
        $message = 'Get all parent categories in trash success !!!';
        $data = [
            'results' => $parentCategories,
            'total' => count($parentCategories)
        ];

        return $this->buildApiResponse(true, $message, $data, Response::HTTP_OK);
    }

    //SUCCESS
    public function getById(int $id): JsonResponse {
        try {
            $parentCategory = $this->parentCategoryRepository->getById($id);

            $message = 'Get Parent Category detail success !!!';
            $statusCode = Response::HTTP_OK;

            return $this->buildApiResponse(true, $message, $parentCategory, $statusCode);
        } catch (\Exception $e) {
            return $this->buildErrorResponse($e->getMessage(), $e->getCode());
        }
    }

    private function validateBrand(int $brandId): Brand {
        $brand = $this->brandRepository->getById($brandId);

        if (empty($brand)) {
            throw new CustomException("Brand id of Parent Category not found in database !!!", Response::HTTP_NOT_FOUND);
        }
        return $brand;
    }

    //SUCCESS
    public function create(array $data): JsonResponse {
        return $this->handleTransaction(function () use ($data) {
            $brand = $this->validateBrand($data['brand_id']);
            $data['brand_name'] =$brand->name;
            $data['created_by'] = auth()->user()->id;
            $data['updated_by'] = auth()->user()->id;
            $parentCategory = $this->parentCategoryRepository->create($data);
            $message = 'Create parent category success !!!';

            return $this->buildApiResponse(true, $message, $parentCategory, Response::HTTP_CREATED);
        });
    }

    //SUCCESS
    public function update(int $id, array $data): JsonResponse {
        return $this->handleTransaction(function () use ($id, $data) {
            $data['updated_by'] = auth()->user()->id;
            $parentCategory = $this->parentCategoryRepository->update($id, $data);
            $message = 'Update Parent Category success !!!';
            return $this->buildApiResponse(true, $message, $parentCategory, Response::HTTP_OK);
        });
    }

    //SUCCESS
    public function moveToTrash(int $id): JsonResponse {
        return $this->handleTransaction(function () use ($id) {
            $this->parentCategoryRepository->moveToTrash($id);
            $message = 'Move parent category to trash success !!!';
            $statusCode = Response::HTTP_OK;

            return $this->buildApiResponse(true, $message, null, $statusCode);
        });
    }

    //SUCCESS
    public function delete(int $id): JsonResponse {
        return $this->handleTransaction(function () use ($id) {
            $this->parentCategoryRepository->delete($id);
            $message = 'Delete parent category to trash success !!!';
            $statusCode = Response::HTTP_OK;

            return $this->buildApiResponse(true, $message, null, $statusCode);
        });
    }

    //SUCCESS
    public function restore(int $id): JsonResponse {
        return $this->handleTransaction(function () use ($id) {
            $this->parentCategoryRepository->restore($id);
            $message = 'Restore parent category success !!!';
            $statusCode = Response::HTTP_OK;

            return $this->buildApiResponse(true, $message, null, $statusCode);
        });
    }
}
