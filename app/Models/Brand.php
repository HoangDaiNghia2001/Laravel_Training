<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'created_by', 'updated_by'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function products() {
        return $this->hasMany(ParentCategory::class);
    }

    public function parentCategories() {
        return $this->hasMany(ParentCategory::class);
    }

    public function childCategories() {
        return $this->hasMany(ChildCategory::class);
    }
}
