<?php

namespace App\Http\Controllers;

use App\Constants\RouteConstants;
use App\Http\Requests\ParentCategoryRequest as ParentRequest;
use App\Request\ParentCategoryRequest;
use App\Services\ParentCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ParentCategoryController extends Controller {

    protected $parentCategoryService;

    public function __construct(ParentCategoryService $parentCategoryService) {
        $this->parentCategoryService = $parentCategoryService;
    }

    //SUCCESS
    public function index(Request $request) {
        return $this->parentCategoryService->getByBrand((int)$request->query('brandId'));
    }

    //SUCCESS
    public function getAllInTrash() {
        return $this->parentCategoryService->getAllInTrash();
    }

    //SUCCESS
    public function show(Request $request) {
        $id =  str_replace('-', '_', RouteConstants::ROUTE_PARENT_CATEGORY);
        return $this->parentCategoryService->getById($request->$id);
    }

    //SUCCESS
    public function save(ParentRequest $request) {
        $data = [
            'brand_id' => $request->brand_id,
            'name' => strtoupper($request->name)
        ];

        $id = $request->id;
        if (empty($id)) {
            return $this->parentCategoryService->create($data);
        } else {
            return $this->parentCategoryService->update($id, $data);
        }
    }

    //SUCCESS
    public function moveToTrash($id) {
        return $this->parentCategoryService->moveToTrash((int)$id);
    }

    //SUCCESS
    public function destroy($id) {
        return $this->parentCategoryService->delete((int)$id);
    }

    //SUCCESS
    public function restore($id) {
        return $this->parentCategoryService->restore((int)$id);
    }
}
