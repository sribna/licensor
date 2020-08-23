<?php

namespace Sribna\Licensor\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Sribna\Licensor\Models\Key;

/**
 * Class KeyRepository
 * @package Sribna\Licensor\Repositories
 */
class KeyRepository extends BaseRepository
{

    /**
     * @var Key
     */
    protected $model;

    /**
     * KeyRepository constructor.
     * @param Key $model
     */
    public function __construct(Key $model)
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

    /**
     * Whether key domain already taken by another user
     * @param string $domain
     * @param int $userId
     * @return bool
     */
    public function domainTakenByAnotherUser($domain, $userId)
    {
        return $this->query()
            ->where('domain', $domain)
            ->where('user_id', '<>', $userId)
            ->exists();
    }
}
