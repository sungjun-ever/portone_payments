<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements RepositoryInterface
{
    protected Model $model;

    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): ?Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): int
    {
        return $this->model->find($id)->update($data);
    }

    public function delete(int $id): mixed
    {
        return $this->model->find($id)->delete();
    }
}