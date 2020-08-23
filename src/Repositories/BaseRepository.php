<?php

namespace Sribna\Licensor\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use RepositoryContract;
use Throwable;

/**
 * Class BaseRepository
 * @package Sribna\Licensor\Repositories
 */
abstract class BaseRepository implements RepositoryContract
{
    /**
     * The Eloquent model
     * @var Model
     */
    protected $model;

    /**
     * Get all of the models from the database.
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Get a new query builder for the model's table.
     * @return Builder
     */
    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * Save the model to the database.
     * @param array $input
     * @return mixed
     */
    public function create(array $input)
    {
        $model = $this->model->newInstance();
        $model->fill($input);
        $saved = $model->save();
        return $saved ? $model : null;
    }

    /**
     * Save the model to the database using transaction.
     * @param array $input
     * @return mixed
     * @throws Throwable
     */
    public function createOrFail(array $input)
    {
        $model = $this->model->newInstance();
        $model->fill($input);
        $saved = $model->saveOrFail();
        return $saved ? $model : null;
    }

    /**
     * Updates the model
     * @param int|string|Model $model
     * @param array $input
     * @return mixed
     */
    public function update($model, array $input)
    {
        if (!is_object($model)) {
            $model = $this->findOrFail($model);
        }

        $model->fill($input);
        $saved = $model->save();
        return $saved ? $model : null;
    }

    /**
     * Loads a single model
     * @param int|string $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->where('id', $id)->first();
    }

    /**
     * Loads a single model or throws an exception if not found
     * @param int|string $id
     * @return mixed
     */
    public function findOrFail($id)
    {
        return $this->model->where('id', $id)->firstOrFail();
    }

    /**
     * Delete the model from the database.
     * @param int|string|Model $model
     * @return bool|null
     * @throws Exception
     */
    public function destroy($model)
    {
        /** @var Model $model */

        if (!is_object($model)) {
            $model = $this->findOrFail($model);
        }

        return $model->delete();
    }
}
