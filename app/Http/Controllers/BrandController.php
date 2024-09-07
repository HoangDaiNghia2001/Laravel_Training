<?php

namespace App\Http\Controllers;

use App\Constants\RoleConstants;
use App\Constants\RouteConstants;
use App\Http\Requests\BrandRequest as BrRequest;
use App\Request\BrandRequest;
use App\Services\BrandService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class BrandController extends Controller {
    use SoftDeletes, HasFactory;

    protected $brandService;

    public function __construct(BrandService $brandService) {
        $this->brandService = $brandService;
    }

    //SUCCESS
    public function index() {
        return $this->brandService->getAll();
    }

    //SUCCESS
    public function show(Request $request) {
        $id = RouteConstants::ROUTE_BRAND;
        return $this->brandService->getById($request->$id);
    }

    //SUCCESS
    public function getAllInTrash() {
        return $this->brandService->getAllInTrash();
    }

    //SUCCESS
    public function save(BrRequest $request) {
        $data = [
            'name' => strtoupper($request->name)
        ];

        $id = $request->id;
        if (empty($id)) {
            return $this->brandService->create($data);
        } else {
            return $this->brandService->update($id, $data);
        }
    }

    //SUCCESS
    public function moveToTrash($id) {
        return $this->brandService->moveToTrash((int)$id);
    }

    //SUCCESS
    public function destroy($id) {
        return $this->brandService->delete((int)$id);
    }

    //SUCCESS
    public function restore($id) {
        return $this->brandService->restore((int)$id);
    }
}
