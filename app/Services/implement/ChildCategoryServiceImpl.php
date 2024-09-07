<?php

namespace App\Services\implement;

use App\Exceptions\CustomException;
use App\Models\ParentCategory;
use App\Repositories\ChildCategoryRepositoryInterface;
use App\Repositories\ParentCategoryRepositoryInterface;
use App\Services\ChildCategoryService;
use App\Traits\MethodTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ChildCategoryServiceImpl implements ChildCategoryService {

    use MethodTrait;

    protected $parentCategoryRepository;
    protected $childCategoryRepository;

    public function __construct(
        ParentCategoryRepositoryInterface $parentCategoryRepository,
        ChildCategoryRepositoryInterface $childCategoryRepository
    ) {
        $this->childCategoryRepository = $childCategoryRepository;
        $this->parentCategoryRepository = $parentCategoryRepository;
    }

    //SUCCESS
    public function getByParentCategory(int $parentCategoryId): JsonResponse {
        try {
            $childCategories = $this->childCategoryRepository->getByParentCategory($parentCategoryId);
            $message = 'Get child categories of parent category: ' . $parentCategoryId . ' in database success !!!';
            $data = [
                'results' => $childCategories,
                'total' => count($childCategories)
            ];

            return $this->buildApiResponse(true, $message, $data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->buildErrorResponse($e->getMessage(), $e->getCode());
        }
    }

    //SUCCESS
    public function getAllInTrash(): JsonResponse {
        $childCategories = $this->childCategoryRepository->getAllInTrash();
        $message = 'Get all child categories in trash success !!!';
        $data = [
            'results' => $childCategories,
            'total' => count($childCategories)
        ];

        return $this->buildApiResponse(true, $message, $data, Response::HTTP_OK);
    }

    //SUCCESS
    public function getById(int $id): JsonResponse {
        try{
            $childCategory = $this->childCategoryRepository->getById($id);

            $message ='Get Child Category detail success !!!';
            $statusCode = Response::HTTP_OK;
    
            return $this->buildApiResponse(true, $message, $childCategory, $statusCode);
        }catch(\Exception $e){
            return $this->buildErrorResponse($e->getMessage(), $e->getCode());
        }
    }

    private function validateParentCategory(int $parentCategoryId): ParentCategory {
        $parentCategory = $this->parentCategoryRepository->getById($parentCategoryId);
        if (empty($parentCategory)) {
            throw new CustomException("Parent category id of child category not found in database !!!", Response::HTTP_NOT_FOUND);
        }
        return $parentCategory;
    }

    //SUCCESS
    public function create(array $data): JsonResponse {
        return $this->handleTransaction(function () use ($data) {
            $parentCategory = $this->validateParentCategory($data['parent_category_id']);
            $data['parent_category_name'] =$parentCategory->name;
            $data['brand_name'] =$parentCategory->brand->name;
            $data['created_by'] = auth()->user()->id;
            $data['updated_by'] = auth()->user()->id;
            $childCategory = $this->childCategoryRepository->create($data);
            $message = "Create child category success !!!";
            return $this->buildApiResponse(true, $message, $childCategory, Response::HTTP_CREATED);
        });
    }

    //SUCCESS
    public function update(int $id, array $data): JsonResponse {
        return $this->handleTransaction(function () use ($id, $data) {
            $data['updated_by'] = auth()->user()->id;
            $childCategory = $this->childCategoryRepository->update($id, $data);
            $message = 'Update Child Category success !!!';
            return $this->buildApiResponse(true, $message, $childCategory, Response::HTTP_OK);
        });
    }

    //SUCCESS
    public function moveToTrash(int $id): JsonResponse {
        return $this->handleTransaction(function () use ($id) {
            $this->childCategoryRepository->moveToTrash($id);
            $message = 'Move child category to trash success !!!';
            $statusCode = Response::HTTP_OK;

            return $this->buildApiResponse(true, $message, null, $statusCode);
        });
    }

    //SUCCESS
    public function delete(int $id): JsonResponse {
        return $this->handleTransaction(function () use ($id) {
            $this->childCategoryRepository->delete($id);
            $message = 'Delete child category success !!!';
            $statusCode = Response::HTTP_OK;

            return $this->buildApiResponse(true, $message, null, $statusCode);
        });
    }

    //SUCCESS
    public function restore(int $id): JsonResponse {
        return $this->handleTransaction(function () use ($id) {
            $this->childCategoryRepository->restore($id);
            $message = 'Restore parent category success !!!';
            $statusCode = Response::HTTP_OK;

            return $this->buildApiResponse(true, $message, null, $statusCode);
        });
    }
}
