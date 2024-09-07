<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChildCategory extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'parent_category_id', 'parent_category_name', 'brand_name', 'created_by', 'updated_by'];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function parentCategory() {
        return $this->belongsTo(ParentCategory::class);
    }

    public function products() {
        return $this->hasMany(Product::class);
    }
}
