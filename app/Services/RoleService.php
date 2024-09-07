<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

interface RoleService {
    public function getAll(): JsonResponse;
    public function getAllIntrash(): JsonResponse;
    public function create(array $data): JsonResponse;
    public function update(int $id, array $data): JsonResponse;
    public function moveToTrash(int $id): JsonResponse;
    public function delete(int $id): JsonResponse;
    public function restore(int $id): JsonResponse;
}
