<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

interface ParentCategoryService{
    public function getByBrand(int $brandId):JsonResponse;
    public function getAllInTrash():JsonResponse;
    public function getById(int $id):JsonResponse;
    public function create(array $data):JsonResponse;
    public function update(int $id, array $data):JsonResponse;
    public function moveToTrash(int $id):JsonResponse;
    public function delete(int $id):JsonResponse;
    public function restore(int $id):JsonResponse;
}