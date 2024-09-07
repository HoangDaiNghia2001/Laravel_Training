<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface extends RepositoryInterface{
    public function getByEmail(string $email): User;
}
