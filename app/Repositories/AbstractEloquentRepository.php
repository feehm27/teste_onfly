<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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

    public function create($data)
    {
        $this->unMountQuery();
        return $this->model->create($data);
    }

    public function find($id)
    {
        $this->unMountQuery();
        return $this->model->find($id);
    }

    public function first(array $columns = ['*'])
    {
        $this->newQuery()->mountWhere();
        $model = $this->query->first($columns);
        $this->unMountQuery();
        return $model;
    }

    public function get(array $columns = ['*'])
    {
        $this->newQuery()->mountWhere();
        $models = $this->query->get($columns);
        $this->unMountQuery();
        return $models;
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        $this->unMountQuery();
        return $this->model->update($attributes, $options);
    }

    public function updateById($id, $data): bool
    {
        $this->unMountQuery();
        return $this->model->find($id)->update($data);
    }

    public function where(string $column, string $value, string $operator = '='): static
    {
        $this->wheres[] = compact('column', 'value', 'operator');
        return $this;
    }

    public function whereIn(string $column, array $value)
    {
        $this->newQuery();
        $model = $this->query->whereIn($column, $value);
        $this->unMountQuery();
        return $model;
    }

    protected function unMountQuery(): void
    {
        $this->wheres = null;
        $this->query = null;
    }

    protected function newQuery(): static
    {
        $this->query = $this->model->newQuery();
        return $this;
    }

    protected function mountWhere(): static
    {
        if (!is_array($this->wheres)) {
            return $this->query;
        }

        foreach ($this->wheres as $where) {
            $this->query->where($where['column'], $where['operator'], $where['value']);
        }

        return $this;
    }
}
