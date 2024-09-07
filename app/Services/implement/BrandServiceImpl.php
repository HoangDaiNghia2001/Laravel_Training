<?php

namespace App\Services\implement;

use App\Repositories\BrandRepositoryInterface;
use App\Services\BrandService;
use App\Traits\MethodTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BrandServiceImpl implements BrandService {

    use MethodTrait;
    protected $brandRepository;

    public function __construct(BrandRepositoryInterface $brandRepository) {
        $this->brandRepository = $brandRepository;
    }

    //SUCCESS
    public function getAll(): JsonResponse {
        $brands = $this->brandRepository->getAll();
        $message = 'Get all brands in database success !!!';
        $data = [
            'results' => $brands,
            'total' => count($brands)
        ];

        return $this->buildApiResponse(true, $message, $data, Response::HTTP_OK);
    }

    //SUCCESS
    public function getAllInTrash(): JsonResponse {
        $brands = $this->brandRepository->getAllInTrash();
        $message = 'Get all brands in trash success !!!';
        $data = [
            'results' => $brands,
            'total' => count($brands)
        ];

        return $this->buildApiResponse(true, $message, $data, Response::HTTP_OK);
    }

    //SUCCESS
    public function getById(int $id): JsonResponse {
        try {
            $brand = $this->brandRepository->getById($id);

            $message = 'Get brand detail success !!!';
            $statusCode = Response::HTTP_OK;

            return $this->buildApiResponse(true, $message, $brand, $statusCode);
        } catch (\Exception $e) {
            return $this->buildErrorResponse($e->getMessage(), $e->getCode());
        }
    }

    //SUCCESS
    public function create(array $data): JsonResponse {
        return $this->handleTransaction(function () use ($data) {
            $data['created_by'] = auth()->user()->id;
            $data['updated_by'] = auth()->user()->id;
            $brand = $this->brandRepository->create($data);
            $message = 'Created brand with name: ' . $data['name'] . ' success !!!';
            $statusCode = Response::HTTP_CREATED;

            return $this->buildApiResponse(true, $message, $brand, $statusCode);
        });
    }

    //SUCCESS
    public function update(int $id, array $data): JsonResponse {
        return $this->handleTransaction(function () use ($id, $data) {
            $data['updated_by'] = auth()->user()->id;
            $brand = $this->brandRepository->update($id, $data);
            $message = 'Update brand success !!!';
            $statusCode = Response::HTTP_OK;

            return $this->buildApiResponse(true, $message, $brand, $statusCode);
        });
    }

    //SUCCESS
    public function moveToTrash(int $id): JsonResponse {
        return $this->handleTransaction(function () use ($id) {
            $this->brandRepository->moveToTrash($id);
            $message = 'Move brand to trash success !!!';
            $statusCode = Response::HTTP_OK;

            return $this->buildApiResponse(true, $message, null, $statusCode);
        });
    }

    //SUCCESS
    public function delete(int $id): JsonResponse {
        return $this->handleTransaction(function () use ($id) {
            $this->brandRepository->delete($id);
            $message ='Delete brand success !!!';
            $statusCode = Response::HTTP_OK;

            return $this->buildApiResponse(true, $message, null, $statusCode);
        });
    }

    //SUCCESS
    public function restore(int $id): JsonResponse {
        return $this->handleTransaction(function () use ($id) {
            $this->brandRepository->restore($id);
            $message ='Restore brand success !!!';
            $statusCode =Response::HTTP_OK;

            return $this->buildApiResponse(true, $message, null, $statusCode);
        });
    }
}
