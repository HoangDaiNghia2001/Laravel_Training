<?php

namespace App\Repositories\implement;

use App\Exceptions\CustomException;
use App\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;

class BaseRepositoryImpl implements RepositoryInterface {
    protected $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function getById(int $id): Model {
        $model = $this->model::find($id);
        return empty($model)
            ? throw new CustomException(class_basename($this->model::class)." not found with id: " . $id . ' !!!', Response::HTTP_NOT_FOUND)
            : $model;
    }
    
    public function getByIdInTrash(int $id): Model {
        $model = $this->model::onlyTrashed()->find($id);
        return empty($model)
            ? throw new CustomException(class_basename($this->model::class)." not found with id: " . $id . ' in trash !!!', Response::HTTP_NOT_FOUND)
            : $model;
    }

    public function getAll():Collection{
        return $this->model->all();
    }

    public function getAllInTrash():Collection{
        return $this->model->onlyTrashed()->get();
    }

    public function create(array $data): Model {
        return $this->model::create($data);
    }

    public function update(int $id, array $data): Model {
        $model = $this->getById($id);
        $model->update($data);
        return $model;
    }

    public function moveToTrash(int $id): void {
        $model = $this->getById($id);
        $model->delete();
    }

    public function delete(int $id): void {
        $model = $this->getByIdInTrash($id);
        $model->forceDelete();
    }

    public function restore(int $id): void {
        $model = $this->getByIdInTrash($id);
        $model->restore();
    }
}
