<?php

namespace App\Http\Controllers;

use App\Constants\RouteConstants;
use App\Http\Requests\ChildCategoryRequest as ChildRequest;
use App\Request\ChildCategoryRequest;
use App\Services\ChildCategoryService;
use Illuminate\Http\Request;

class ChildCategoryController extends Controller {

    protected $childCategoryService;

    public function __construct(ChildCategoryService $childCategoryService) {
        $this->childCategoryService = $childCategoryService;
    }

    //SUCCESS
    public function index(Request $request) {
        return $this->childCategoryService->getByParentCategory((int)$request->query('parentCategoryId'));
    }

    //SUCCESS
    public function getAllInTrash() {
        return $this->childCategoryService->getAllInTrash();
    }

    //SUCCESS
    public function show(Request $request) {
        $id =  str_replace('-', '_', RouteConstants::ROUTE_CHILD_CATEGORY);
        return $this->childCategoryService->getById($request->$id);
    }

    //SUCCESS
    public function save(ChildRequest $request) {
        $data = [
            'parent_category_id' => $request->brand_id,
            'name' => strtoupper($request->name)
        ];

        $id = $request->id;
        if (empty($id)) {
            return $this->childCategoryService->create($data);
        } else {
            return $this->childCategoryService->update($id, $data);
        }
    }

    //SUCCESS
    public function moveToTrash($id) {
        return $this->childCategoryService->moveToTrash((int)$id);
    }

    //SUCCESS
    public function destroy($id) {
        return $this->childCategoryService->delete((int)$id);
    }

    //SUCCESS
    public function restore($id) {
        return $this->childCategoryService->restore((int)$id);
    }
}
