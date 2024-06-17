<?php

namespace App\Repositories;

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

    public function create(array $data) {
        return $this->model::query()->insertGetId($data);
    }

    public function save($model)
    {
        return $model->save();
    }

    public function update($model, array $data) {
        return $model->update($data);
    }

    public function updateWhereIn($field, $values, array $data): int
    {
       return $this->model::query()->whereIn($field, $values)->update($data);
    }

    public function findById(int $id, $relations = null)
    {
        $builder = $this->model::query();
        return $this->relations($builder, $relations)->find($id);
    }

    public function findWithRelations($relations, int $limit = 0, int $offset = 0)
    {
        $builder = $this->model::query();
        return $this->relations($builder, $relations)->take($limit)->skip($offset);
    }

   public function relations($builder, string|array $relations)
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

   public function filter($builder)
   {

   }

}
