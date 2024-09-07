<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface ParentCategoryRepositoryInterface extends RepositoryInterface{
    public function getByBrand(?int $brandId): Collection;
}
