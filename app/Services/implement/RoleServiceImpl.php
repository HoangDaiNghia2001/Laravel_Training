<?php

namespace App\Services\implement;

use App\Repositories\RoleRepositoryInterface;
use App\Services\RoleService;
use App\Traits\MethodTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RoleServiceImpl implements RoleService {

    use MethodTrait;

    protected $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository) {
        $this->roleRepository = $roleRepository;
    }

    //SUCCESS
    public function getAll(): JsonResponse {
        $roles = $this->roleRepository->getAll();
        $message = 'Get all roles in database success !!!';
        $data = [
            'results' => $roles,
            'total' => count($roles)
        ];

        return $this->buildApiResponse(true, $message, $data, Response::HTTP_OK);
    }

    //SUCCESS
    public function getAllIntrash(): JsonResponse {
        $roles = $this->roleRepository->getAllInTrash();
        $message = 'Get all roles in trash success !!!';
        $data = [
            'results' => $roles,
            'total' => count($roles)
        ];

        return $this->buildApiResponse(true, $message, $data, Response::HTTP_OK);
    }

    //SUCCESS
    public function create(array $data): JsonResponse {
        return $this->handleTransaction(function () use ($data) {
            $data['created_by'] = auth()->user()->id;
            $data['updated_by'] = auth()->user()->id;
            $role = $this->roleRepository->create($data);
            $message = 'Create role with name: ' . $data['name'] . ' success !!!';

            return $this->buildApiResponse(true, $message, $role, Response::HTTP_CREATED);
        });
    }

    //SUCCESS
    public function update(int $id, array $data): JsonResponse {
        return $this->handleTransaction(function () use ($id, $data) {
            $data['updated_by'] = auth()->user()->id;
            $role = $this->roleRepository->update($id, $data);
            $message = 'Update role success !!!';

            return $this->buildApiResponse(true, $message, $role, Response::HTTP_OK);
        });
    }

    //SUCCESS
    public function moveToTrash(int $id): JsonResponse {
        return $this->handleTransaction(function () use ($id) {
            $this->roleRepository->moveToTrash($id);
            $message = 'Move role to trash success !!!';
            $statusCode = Response::HTTP_OK;

            return $this->buildApiResponse(true, $message, null, $statusCode);
        });
    }

    //SUCCESS
    public function delete(int $id): JsonResponse {
        return $this->handleTransaction(function () use ($id) {
            $this->roleRepository->delete($id);
            $message = 'Delete role success !!!';
            $statusCode = Response::HTTP_OK;

            return $this->buildApiResponse(true, $message, null, $statusCode);
        });
    }

    //SUCCESS
    public function restore(int $id): JsonResponse {
        return $this->handleTransaction(function () use ($id) {
            $this->roleRepository->restore($id);
            $message = 'Restore role success !!!';
            $statusCode = Response::HTTP_OK;

            return $this->buildApiResponse(true, $message, null, $statusCode);
        });
    }
}
