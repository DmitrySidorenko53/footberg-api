<?php

namespace App\Repositories;

use App\Exceptions\InvalidIncomeTypeException;
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

    public function findWithFilters(array $filters, $relations = null)
    {
        $builder = $this->model::query();

        if (!$filters) {
            return $this->findWithRelations($relations);
        }

        foreach ($filters as $filter) {
            $this->addFilter($builder, $filter);
        }
        return $this->relations($builder, $relations);
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

    public function findWithRelations($relations, int $limit = null, int $offset = null)
    {
        $builder = $this->model::query();
        $builder =  $this->relations($builder, $relations);

        if ($limit) {
            $builder->take($limit);
        }

        if ($offset) {
            $builder->skip($offset);
        }

        return $builder;
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

    public function deleteWithFilters(array $filters, $relations = null)
    {
        $builder = $this->findWithFilters($filters, $relations);
        return $builder->delete();
    }

    public function countWithFilters($filters)
    {
        return $this->findWithFilters($filters)->count();
    }

    private function relations($builder, array|string $relations)
    {
        if (!$relations) {
            return $builder;
        }

        return $builder->with($relations);
    }

    private function addFilter(\Illuminate\Database\Query\Builder $builder, $filter)
    {
        //todo implement
    }
}
