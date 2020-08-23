<?php

namespace Sribna\Licensor\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Sribna\Licensor\Models\Plan;

/**
 * Class PlanRepository
 * @package Sribna\Licensor\Repositories
 */
class PlanRepository extends BaseRepository
{
    /**
     * @var Plan
     */
    protected $model;

    /**
     * PlanRepository constructor.
     * @param Plan $model
     */
    public function __construct(Plan $model)
    {
        $this->model = $model;
    }

    /**
     * Returns a collection of enabled models
     * @return Collection
     */
    public function getActive(): Collection
    {
        return $this->model->active()
            ->orderBy('weight', 'asc')
            ->get();
    }
}
