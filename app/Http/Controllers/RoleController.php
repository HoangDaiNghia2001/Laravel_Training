<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequests;
use App\Services\RoleService;

class RoleController extends Controller {
    protected $roleService;

    public function __construct(RoleService $roleService) {
        $this->roleService = $roleService;
    }

    //SUCCESS
    public function index() {
        return $this->roleService->getAll();
    }

    //SUCCESS
    public function getAllInTrash() {
        return $this->roleService->getAllInTrash();
    }

    //SUCCESS
    public function save(RoleRequests $request) {
        $data = [
            'name' => strtoupper($request->name)
        ];

        $id = $request->id;

        if (empty($id)) {
            return $this->roleService->create($data);
        } else {
            return $this->roleService->update($id, $data);
        }
    }

    //SUCCESS
    public function moveToTrash($id) {
        return $this->roleService->moveToTrash((int)$id);
    }

    //SUCCESS
    public function destroy($id) {
        return $this->roleService->delete((int)$id);
    }

    //SUCCESS
    public function restore($id) {
        return $this->roleService->restore((int)$id);
    }
}
