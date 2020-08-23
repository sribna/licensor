<?php

namespace Sribna\Licensor\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Sribna\Licensor\Traits\HasStatusAttribute;

/**
 * Class Plan
 *
 * @property int $id
 * @property string $title
 * @property string $text
 * @property int $lifetime
 * @property boolean $status
 * @property int $price
 * @property int $weight
 *
 * @property Collection $features
 * @property Collection $keys
 *
 * @method Builder free()
 * @method Builder ofFeature($feature)
 * @method Builder ofActiveFeature($feature)
 */
class Plan extends Model
{
    use HasStatusAttribute;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'text',
        'lifetime',
        'status',
        'price',
        'weight'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => 'boolean'
    ];

    /**
     * Load related key models
     * @return HasMany
     */
    public function keys()
    {
        return $this->hasMany(Key::class);
    }

    /**
     * Load related feature models
     * @return BelongsToMany
     */
    public function features()
    {
        return $this->belongsToMany(Feature::class);
    }

    /**
     * Whether the plan has no price
     * @return bool
     */
    public function isFree()
    {
        return $this->price <= 0;
    }

    /**
     * Filter plans without price
     * @param Builder $query
     * @return Builder
     */
    public function scopeFree(Builder $query)
    {
        return $query->where('price', '<=', 0);
    }

    /**
     * Filter by feature
     * @param Builder $query
     * @param string|array $featureId
     * @return Builder
     */
    public function scopeOfFeature(Builder $query, $featureId)
    {
        return $query->whereHas('features', function (Builder $query) use ($featureId) {
            return $query->whereIn('features.id', (array)$featureId);
        });
    }

    /**
     * Filter by active features
     * @param Builder $query
     * @param string|array $featureId
     * @return Builder
     */
    public function scopeOfActiveFeature(Builder $query, $featureId)
    {
        return $query->whereHas('features', function (Builder $query) use ($featureId) {
            return $query->whereIn('features.id', (array)$featureId)->active();
        });
    }

    /**
     * Whether the plan has specific feature(s)
     * @param string|array $featureId
     * @return bool
     */
    public function hasFeature($featureId): bool
    {
        return $this->features()
            ->whereIn('features.id', (array)$featureId)
            ->exists();
    }

    /**
     * Whether the plan has specific active feature(s)
     * @param string|array $featureId
     * @return bool
     */
    public function hasActiveFeature($featureId): bool
    {
        return $this->features()
            ->active()
            ->whereIn('features.id', (array)$featureId)
            ->exists();
    }

    /**
     * Whether the plan has expiration
     * @return bool
     */
    public function isExpirable()
    {
        return isset($this->lifetime);
    }
}
