<?php

namespace App\Models;

use Carbon\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'description', 'title', 'price', 'discounted_percent', 'discounted_price',
        'quantity', 'main_image', 'color', 'brand_id', 'brand_name', 'parent_category_id',
        'parent_category_name', 'child_category_id', 'child_category_name', 'created_by',
        'updated_by'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function parentCategory() {
        return $this->belongsTo(ParentCategory::class);
    }

    public function childCategory() {
        return $this->belongsTo(ChildCategory::class);
    }
}
