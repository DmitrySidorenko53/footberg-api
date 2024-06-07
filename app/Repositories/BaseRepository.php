<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;

abstract class BaseRepository
{
    protected mixed $model;
    private $builder;

    public function getBuilder(): mixed
    {
        return $this->builder;
    }

    public function setBuilder(): void
    {
        $this->builder = $this->model->newQuery();
    }

    abstract protected function setModel();

    public function create(array $data) {
        return $this->model->insertGetId($data);
    }

    public function save($model)
    {
        return $model->save();
    }

    public function update($model, array $data) {

    }

    public function findById(int $id) {
        return $this->model->find($id);
    }

    public function findByField(string $field, mixed $value, $operator = '=') {

    }

    public function findByFields(array $fields) {

    }


}
