<?php

namespace App\Repositories\implement;

use App\Models\ParentCategory;
use App\Repositories\ParentCategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ParentCategoryRepository extends BaseRepositoryImpl implements ParentCategoryRepositoryInterface {

    public function __construct(ParentCategory $parentCategory) {
        parent::__construct($parentCategory);
    }

    //SUCCESS
    public function getByBrand(?int $brandId): Collection {
        return ParentCategory::where('brand_id', '=', $brandId)->get();
    }
}
