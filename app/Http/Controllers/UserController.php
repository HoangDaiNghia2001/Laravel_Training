<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\UserService;
use App\Traits\MethodTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller {

    use MethodTrait;

    protected $userService;

    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    // SUCCESS
    public function save(RegisterRequest $request) {
        if (empty(auth()->user())) {
            return $this->userService->register($request->all());
        } else {
            return $this->userService->updateInformation($request->all());
        }
    }

    //SUCCESS
    public function login(LoginRequest $request) {
        return $this->userService->login($request->all());
    }

    //SUCCESS
    public function logout() {
        auth()->logout();
        
        return $this->buildApiResponse(true,'Logout success !!!', null, Response::HTTP_OK);
    }

    //SUCCESS
    public function refreshToken(Request $request) {
        $refreshToken = $request->refreshToken;
        return $this->userService->refeshToken($refreshToken);
    }

    //SUCCESS
    public function getInformation() {
        return $this->buildApiResponse(true,"Get information of user success !!!", auth()->user(), Response::HTTP_OK);
    }

    //SUCCESS
    public function moveToTrash() {
        return $this->userService->moveToTrash(auth()->user()->id);
    }

    //SUCCESS
    public function delete($id) {
        return $this->userService->delete((int)$id);
    }

    //SUCCESS
    public function restore($id) {
        return $this->userService->restore((int)$id);
    }
}
