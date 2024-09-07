<?php

namespace App\Repositories\implement;

use App\Models\ChildCategory;
use App\Repositories\ChildCategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ChildCategoryRepository extends BaseRepositoryImpl implements ChildCategoryRepositoryInterface {

    public function __construct(ChildCategory $childCategory) {
        parent::__construct($childCategory);
    }

    //SUCCESS
    public function getByParentCategory(?int $parentCategoryId): Collection {
        return ChildCategory::where('parent_category_id', '=', $parentCategoryId)->get();
    }
}
