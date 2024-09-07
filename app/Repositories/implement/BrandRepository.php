<?php

namespace App\Repositories\implement;

use App\Models\Brand;
use App\Repositories\BrandRepositoryInterface;

class BrandRepository extends BaseRepositoryImpl implements BrandRepositoryInterface{

    public function __construct(Brand $brand)
    {
        parent::__construct($brand);
    }
}
