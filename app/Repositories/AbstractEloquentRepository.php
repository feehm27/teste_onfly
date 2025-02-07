<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class AbstractEloquentRepository
{
    protected $model;
    protected $wheres;
    protected $query;

    public function __construct()
    {
        $this->model = $this->resolveModel();
    }

    abstract protected function resolveModel(): Model;

    public function all(): Collection
    {
        $this->unMountQuery();
        return $this->model->all();
    }

    protected function unMountQuery(): void
    {
        $this->wheres = null;
        $this->query = null;
    }

    public function create($data)
    {
        $this->unMountQuery();
        return $this->model->create($data);
    }

    public function first(array $columns = ['*'])
    {
        $this->newQuery()->mountWhere();
        $model = $this->query->first($columns);
        $this->unMountQuery();
        return $model;
    }

    protected function mountWhere(): static
    {
        if (!is_array($this->wheres) || empty($this->wheres)) {
            return $this->newQuery();
        }

        foreach ($this->wheres as $where) {
            $this->query->where($where['column'], $where['operator'], $where['value']);
        }

        return $this;
    }

    public function where(string $column, string $value, string $operator = '='): static
    {
        $this->wheres[] = compact('column', 'value', 'operator');
        return $this;
    }

    protected function newQuery(): static
    {
        $this->query = $this->model->newQuery();
        return $this;
    }

    public function get(array $columns = ['*'])
    {
        if (!$this->query) {
            $this->newQuery()->mountWhere();
        }

        $models = $this->query->get($columns);
        $this->unMountQuery();
        return $models;
    }

    public function orderBy(string $column, string $direction = 'asc'): static
    {
        if (!$this->query) {
            $this->newQuery()->mountWhere();
        }

        $this->query->orderBy($column, $direction);
        return $this;
    }

    public function paginate(int $perPage = 10, int $page = 1, array $columns = ['*'], string $pageName = 'page'): LengthAwarePaginator
    {
        if (!$this->query) {
            $this->newQuery()->mountWhere();
        }

        $models = $this->query->paginate($perPage, $columns, $pageName, $page);
        $this->unMountQuery();
        return $models;
    }

    public function updateById($id, $data): bool
    {
        $this->unMountQuery();
        return $this->model->find($id)->update($data);
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        $this->unMountQuery();
        return $this->model->update($attributes, $options);
    }

    public function find($id)
    {
        $this->unMountQuery();
        return $this->model->find($id);
    }

    public function whereLike(string $column, string $value, $returnOnlyQuery = false)
    {
        $this->newQuery();

        if ($returnOnlyQuery) {
            $this->query->where($column, 'LIKE', '%' . $value . '%');

            return $this->query;
        }

        return $this->where($column, '%' . $value . '%', 'LIKE');
    }

    public function whereIn(string $column, array $value)
    {
        $this->newQuery();
        $model = $this->query->whereIn($column, $value);
        $this->unMountQuery();
        return $model;
    }
}
