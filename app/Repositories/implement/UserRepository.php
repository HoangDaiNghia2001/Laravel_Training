<?php

namespace App\Repositories\implement;

use App\Exceptions\CustomException;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\Request\UserRequest;
use Symfony\Component\HttpFoundation\Response;

class UserRepository extends BaseRepositoryImpl implements UserRepositoryInterface {

    public function __construct(User $user) {
        parent::__construct($user);
    }

    //SUCCESS
    public function getByEmail(string $email): User {
        $user = User::where('email', '=', $email)->first();
        return empty($user)
            ? throw new CustomException("User not found with email: " . $email, Response::HTTP_NOT_FOUND)
            : $user;
    }
}
