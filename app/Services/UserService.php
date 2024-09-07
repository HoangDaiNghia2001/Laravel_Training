<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\JsonResponse;

interface UserService {
    public function login(array $data): JsonResponse;
    public function register(array $data): JsonResponse;
    public function createRefreshToken(User $user): string;
    public function refeshToken(string $refreshToken): JsonResponse;
    public function updateInformation(array $data): JsonResponse;
    public function getInformation(int $id): JsonResponse;
    public function moveToTrash($id):JsonResponse;
    public function delete($id):JsonResponse;
    public function restore($id):JsonResponse;
}
