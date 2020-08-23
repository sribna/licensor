<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface RepositoryContract
 */
interface RepositoryContract
{

    /**
     * Get all of the models from the database.
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get a new query builder for the model's table.
     * @return Builder
     */
    public function query(): Builder;

    /**
     * Save the model to the database.
     * @param array $input
     * @return mixed
     */
    public function create(array $input);

    /**
     * Save the model to the database using transaction.
     * @param array $input
     * @return mixed
     * @throws Throwable
     */
    public function createOrFail(array $input);

    /**
     * Updates the model
     * @param int|string|Model $model
     * @param array $input
     * @return mixed
     */
    public function update($model, array $input);

    /**
     * Loads a single model
     * @param int|string $id
     * @return mixed
     */
    public function find($id);

    /**
     * Loads a single model or throws an exception if not found
     * @param int|string $id
     * @return mixed
     */
    public function findOrFail($id);

    /**
     * Delete the model from the database.
     * @param int|string|Model $model
     * @return bool|null
     * @throws Exception
     */
    public function destroy($model);
}
