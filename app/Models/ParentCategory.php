<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParentCategory extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'brand_id', 'brand_name', 'created_by', 'updated_by'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function childCategories() {
        return $this->hasMany(ChildCategory::class);
    }

    public function products() {
        return $this->hasMany(Product::class);
    }
}
