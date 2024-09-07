<?php

namespace App\Repositories;

use App\Models\Role;

interface RoleRepositoryInterface extends RepositoryInterface{
    public function getRoleByName(string $name): Role;
}
