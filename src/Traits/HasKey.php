<?php

namespace Sribna\Licensor\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Sribna\Licensor\Models\Key;

/**
 * Trait HasKey
 * @package Sribna\Licensor\Traits
 *
 * @property Collection $keys
 */
trait HasKey
{

    /**
     * Load user related keys
     * @return HasMany
     */
    public function keys(): HasMany
    {
        return $this->hasMany(Key::class);
    }

    /**
     * Whether the user has specific key feature
     * @param string|array $featureId
     * @return bool
     */
    public function hasKeyFeature($featureId)
    {
        return $this->keys()->valid()
            ->whereHas('plan', function ($plan) use ($featureId) {
                $plan->ofActiveFeature($featureId);
            })->exists();
    }

    /** Trait contract */

    /**
     * @param $related
     * @param null $foreignKey
     * @param null $localKey
     * @return mixed
     */
    abstract public function hasMany($related, $foreignKey = null, $localKey = null);

    /**
     * @return mixed
     */
    abstract public function getAuthIdentifier();

}
