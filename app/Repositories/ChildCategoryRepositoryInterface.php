<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface ChildCategoryRepositoryInterface extends RepositoryInterface{
    public function getByParentCategory(?int $parentCayegoryId): Collection;
}
