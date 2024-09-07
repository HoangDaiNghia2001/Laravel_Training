<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject {
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    protected $fillable = ['full_name', 'email', 'phone', 'address', 'password'];

    public function roles() {
        return $this->belongsToMany(Role::class);
    }

    public function brands() {
        return $this->belongsToMany(Brand::class);
    }

    public function parentCategories() {
        return $this->belongsToMany(ParentCategory::class);
    }

    public function childCategories() {
        return $this->belongsToMany(ChildCategory::class);
    }

    public function products() {
        return $this->belongsToMany(Product::class);
    }

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [
            'email' => $this->email,
            'id' => $this->id
        ];
    }
}
