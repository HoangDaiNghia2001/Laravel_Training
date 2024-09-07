<?php

namespace App\Services\implement;

use App\Constants\RoleConstants;
use App\Events\DeleteUserEvent;
use App\Events\RestoreUserEvent;
use App\Events\SoftDeleteUserEvent;
use App\Models\User;
use App\Repositories\RoleRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\UserService;
use App\Traits\MethodTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserServiceImpl implements UserService {

    use MethodTrait;

    protected $roleRepository;
    protected $userRepository;

    public function __construct(
        RoleRepositoryInterface $role,
        UserRepositoryInterface $userRepository,
    ) {
        $this->roleRepository = $role;
        $this->userRepository = $userRepository;
    }

    // SUCCESS
    public function login(array $data): JsonResponse {
        try {
            $user = $this->userRepository->getByEmail($data['email']);

            if (!Hash::check($data['password'], $user->password)) {
                return $this->buildErrorResponse('Password does not match !!!', Response::HTTP_UNAUTHORIZED);
            }
            $message = 'Login success !!!';
            $data = [
                'access_token' => JWTAuth::fromUser($user),
                'refresh_token' => $this->createRefreshToken($user)
            ];

            return $this->buildApiResponse(true, $message, $data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->buildErrorResponse($e->getMessage(), $e->getCode());
        }
    }

    //SUCCESS
    public function register(array $data): JsonResponse {
        return $this->handleTransaction(function () use ($data) {
            $user = $this->userRepository->create($data);
            $role = $this->roleRepository->getRoleByName(RoleConstants::USER_ROLE);

            $user->roles()->attach($role->id);

            $message = 'Register success !!!';
            $data = 'Register success with email ' . $user->email . ' .Please login agains!!!';
            return $this->buildApiResponse(true, $message, $data, Response::HTTP_CREATED);
        });
    }

    //SUCCESS
    public function createRefreshToken(User $user): string {
        $data = [
            'id' => $user->id,
            'email' => $user->email,
            'radom' => rand() . time(),
            'expired' => time() + config('jwt.refresh_ttl')
        ];
        return JWTAuth::getJWTProvider()->encode($data);
    }
   
    //SUCCESS
    public function refeshToken(string $refreshToken): JsonResponse {
        try {
            $refreshTokenDecode = JWTAuth::getJWTProvider()->decode($refreshToken);

            $user = $this->userRepository->getByEmail($refreshTokenDecode['email']);

            $token = JWTAuth::fromUser($user);

            $refreshToken = $this->createRefreshToken($user);

            $message = 'Refresh token success !!!';
            $data = [
                'access_token' => $token,
                'refresh_token' => $this->createRefreshToken($user)
            ];

            return $this->buildApiResponse(true, $message, $data, Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    //SUCCESS
    public function updateInformation(array $data): JsonResponse {
        return $this->handleTransaction(function () use ($data) {
            $id = auth()->user()->id;
            $user = $this->userRepository->update($id, $data);

            $message = 'Update user success !!!';

            return $this->buildApiResponse(true, $message, $user, Response::HTTP_OK);
        });
    }

    //SUCCESS
    public function getInformation(int $id): JsonResponse {
        try {
            $user = $this->userRepository->getById($id);
            $message = "Get information of user success !!!";

            return $this->buildApiResponse(true, $message, $user, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->buildErrorResponse($e->getMessage(), $e->getCode());
        }
    }

    //SUCCESS
    public function moveToTrash($id): JsonResponse {
        return $this->handleTransaction(function () use ($id) {
            $this->userRepository->moveToTrash($id);
            $message = 'Move user to trash success !!!';
            $statusCode = Response::HTTP_OK;

            event(new SoftDeleteUserEvent($this->userRepository->getByIdInTrash($id)));
            
            return $this->buildApiResponse(true, $message, null, $statusCode);
        });
    }

    //SUCCESS
    public function delete($id): JsonResponse {
        return $this->handleTransaction(function () use ($id) {
            event(new DeleteUserEvent($this->userRepository->getByIdInTrash($id)));

            $this->userRepository->delete($id);
            $message = 'Delete user in trash success !!!';
            $statusCode = Response::HTTP_OK;


            return $this->buildApiResponse(true, $message, null, $statusCode);
        });
    }

    //SUCCESS
    public function restore($id): JsonResponse {
        return $this->handleTransaction(function () use ($id) {
            $this->userRepository->restore($id);
            $message = 'Restore user from trash success !!!';
            $statusCode = Response::HTTP_OK;

            event(new RestoreUserEvent($this->userRepository->getById($id)));

            return $this->buildApiResponse(true, $message, null, $statusCode);
        });
    }
}
