<?php

namespace App\Repositories;

interface RepositoryInterface
{
    /**
     * Get all records with eager loaded relations
     */
    public function all(array $columns = ['*']);

    /**
     * Get paginated records
     */
    public function paginate(int $perPage = 15, array $columns = ['*']);

    /**
     * Find record by ID
     */
    public function find(int $id);

    /**
     * Find record by attribute
     */
    public function findByAttribute(string $attribute, $value);

    /**
     * Create a new record
     */
    public function create(array $data);

    /**
     * Update a record
     */
    public function update(int $id, array $data);

    /**
     * Delete a record
     */
    public function delete(int $id): bool;

    /**
     * Get count of records
     */
    public function count();
}
