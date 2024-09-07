<?php

namespace App\Repositories\implement;

use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository  extends BaseRepositoryImpl implements ProductRepositoryInterface {

    public function __construct(Product $product)
    {
        parent::__construct($product);
    }

    //SUCCESS
    public function filter(?string $name, ?int $brandId, ?int $parentCategoryId, ?int $childCategoryId, ?string $color, ?float $minPrice, ?float $maxPrice, ?string $sortBy): Collection {
        $query = Product::query();

        // Apply conditions based on the parameters
        if ($name !== null) {
            $query->where('name', 'like', '%' . $name . '%');
        }
        if ($brandId !== null) {
            $query->where('brand_id', $brandId);
        }
        if ($parentCategoryId !== null) {
            $query->where('parent_category_id', $parentCategoryId);
        }
        if ($childCategoryId !== null) {
            $query->where('child_category_id', $childCategoryId);
        }
        if ($color !== null) {
            $query->where('color', $color);
        }
        if ($minPrice !== null && $maxPrice !== null) {
            $query->whereBetween('discounted_price', [$minPrice, $maxPrice]);
        }

        // Apply sorting based on the $sort parameter
        if ($sortBy === 'price_low') {
            $query->orderBy('discounted_price', 'asc');
        } elseif ($sortBy === 'price_high') {
            $query->orderBy('discounted_price', 'desc');
        } elseif ($sortBy === 'newest') {
            $query->orderBy('id', 'desc');
        }

        // Execute the query and return the result
        return $query->get();
    }

    //SUCCESS
    public function getByIdUser(int $idUser): Collection {
        return Product::where("created_by", $idUser)->get();
    }

    //SUCCESS
    public function getByIdUserInTrash(int $idUser): Collection {
        return Product::onlyTrashed()->where("created_by", $idUser)->get();
    }
}
