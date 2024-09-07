<?php

namespace App\Repositories\implement;

use App\Exceptions\CustomException;
use App\Models\Role;
use App\Repositories\RoleRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class RoleRepository extends BaseRepositoryImpl implements RoleRepositoryInterface {

    public function __construct(Role $role) {
        parent::__construct($role);
    }

    //SUCCESS
    public function getRoleByName(string $name): Role {
        $role =  Role::where('name', '=', $name)->first();
        return empty($role)
            ?  throw new CustomException("Role not found with name: " . $name, Response::HTTP_NOT_FOUND)
            : $role;
    }
}
