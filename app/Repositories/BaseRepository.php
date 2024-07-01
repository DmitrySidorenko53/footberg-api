<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;

abstract class BaseRepository
{
    protected mixed $model;

    /**
     * @param mixed $model
     */
    public function __construct(mixed $model)
    {
        $this->model = $model;
    }

    public function insertGetId(array $data)
    {
        return $this->model::query()->insertGetId($data);
    }

    public function insert($data)
    {
        return $this->model::query()->insert($data);
    }

    public function insertIgnore($data)
    {
        return $this->model::query()->insertOrIgnore($data);
    }

    public function save($model)
    {
        return $model->save();
    }

    public function updateOrInsert($model, $data)
    {
        return $this->model::query()->updateOrInsert($model, $data);
    }

    public function update($model, array $data)
    {
        return $model->update($data);
    }

    public function updateWhereIn($field, $values, array $data): int
    {
        return $this->model::query()->whereIn($field, $values)->update($data);
    }

    public function findWithFilters(array $filters)
    {
        $builder = $this->model::query();
        foreach ($filters as $field => $value) {
            $builder->where($field, $value);
        }
        return $builder;
    }

    public function findById(int $id, $relations = null)
    {
        $builder = $this->model::query();
        return $this->relations($builder, $relations)->find($id);
    }

    public function findBy($field, $value, $relations = null): Builder
    {
        $builder = $this->model::query();
        return $this->relations($builder, $relations)->where($field, $value);
    }

    public function findWithRelations($relations, int $limit = 0, int $offset = 0)
    {
        $builder = $this->model::query();
        return $this->relations($builder, $relations)->take($limit)->skip($offset);
    }

    public function countBy($field, $value, $relations = null): int
    {
        return $this->findBy($field, $value, $relations)->count();
    }


    public function delete($model)
    {
        return $model->delete();
    }

    public function deleteById(int $id)
    {
        return $this->model::query()->destroy($id);
    }

    public function deleteBy(string $field, $value)
    {
        return $this->model::query()->where($field, $value)->delete();
    }

    public function deleteWhereIn($field, $values): int
    {
        return $this->model::query()->whereIn($field, $values)->delete();
    }

    public function deleteWithFilters(array $filters)
    {
        $builder = $this->model::query();
        foreach ($filters as $field => $value) {
            $builder->where($field, $value);
        }
        return $builder->delete();
    }

    private function relations($builder, $relations)
    {
        if (!$relations) {
            return $builder;
        }

        if (is_string($relations)) {
            $builder->with($relations);
            return $builder;
        }

        foreach ($relations as $relation) {
            $builder->with($relation);
        }

        return $builder;
    }

    public function filter($builder, array $filters)
    {

    }
}
