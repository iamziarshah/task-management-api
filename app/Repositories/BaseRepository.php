<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements RepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records with eager loaded relations
     */
    public function all(array $columns = ['*'])
    {
        return $this->model->with($this->getRelations())->get($columns);
    }

    /**
     * Get paginated records with eager loading
     */
    public function paginate(int $perPage = 15, array $columns = ['*'])
    {
        return $this->model->with($this->getRelations())->paginate($perPage, $columns);
    }

    /**
     * Find record by ID with relations
     */
    public function find(int $id)
    {
        return $this->model->with($this->getRelations())->find($id);
    }

    /**
     * Find record by attribute
     */
    public function findByAttribute(string $attribute, $value)
    {
        return $this->model->with($this->getRelations())
            ->where($attribute, $value)
            ->first();
    }

    /**
     * Create a new record
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update a record
     */
    public function update(int $id, array $data)
    {
        $record = $this->model->find($id);

        if ($record) {
            $record->update($data);
        }

        return $record;
    }

    /**
     * Delete a record
     */
    public function delete(int $id): bool
    {
        $record = $this->model->find($id);

        return $record ? $record->delete() : false;
    }

    /**
     * Get count of records
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * Get relations to eager load - can be overridden in child classes
     * This demonstrates query optimization with eager loading
     */
    protected function getRelations(): array
    {
        return [];
    }
}
