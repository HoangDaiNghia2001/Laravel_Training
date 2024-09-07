<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface {
    public function getById(int $id): Model;
    public function getByIdInTrash(int $id): Model;
    public function getAll():Collection;
    public function getAllInTrash():Collection;
    public function create(array $data): Model;
    public function update(int $id, array $data): Model;
    public function moveToTrash(int $id): void;
    public function delete(int $id): void;
    public function restore(int $id): void;
}
