<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface extends RepositoryInterface{
    public function filter(?string $name, ?int $brandId, ?int $parentCategoryId, ?int $childCategoryId, ?string $color, ?float $minPrice, ?float $maxPrice, ?string $sortBy): Collection;
    public function getByIdUser(int $idUser):Collection;
    public function getByIdUserInTrash(int $idUser):Collection;
}
