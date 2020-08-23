<?php

namespace Sribna\Licensor\Repositories;


use Illuminate\Database\Eloquent\Collection;
use Sribna\Licensor\Models\Secret;

/**
 * Class SecretRepository
 * @package Sribna\Licensor\Repositories
 */
class SecretRepository extends BaseRepository
{
    /**
     * @var Secret
     */
    protected $model;

    /**
     * SecretRepository constructor.
     * @param Secret $model
     */
    public function __construct(Secret $model)
    {
        $this->model = $model;
    }

    /**
     * Returns a collection of enabled models
     * @return Collection
     */
    public function getActive(): Collection
    {
        return $this->model->active()->get();
    }

}
