<?php

namespace Sribna\Licensor\Models;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Sribna\Licensor\Traits\HasStatusAttribute;

/**
 * Class Key
 *
 * @property string $id
 * @property int $plan_id
 * @property int $user_id
 * @property boolean $status
 * @property string $domain
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $activated_at
 * @property Carbon|null $expires_at
 *
 * @property User|null $user
 * @property Plan|null $plan
 *
 * @method Builder ofPlan(int $planId)
 * @method Builder ofDomain(string $domain)
 * @method Builder ofDomainLike(string $domain)
 * @method Builder activated()
 * @method Builder valid()
 */
class Key extends Model
{

    use HasStatusAttribute;

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'id',
        'status',
        'plan_id',
        'user_id',
        'domain',
        'activated_at'
    ];

    /**
     * The attributes that should be cast.
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
        'activated_at' => 'datetime'
    ];

    /**
     * Whether the key has been activated
     * @return bool
     */
    public function isActivated()
    {
        return isset($this->activated_at);
    }

    /**
     * Getter for "expires_at" property
     * @return \Carbon\Carbon|null
     */
    public function getExpiresAtAttribute()
    {
        if ($this->isActivated() && $this->plan->isExpirable()) {
            return $this->activated_at->addSeconds($this->plan->lifetime);
        }

        return null;
    }

    /**
     * Related user model
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('licensor.config.user_model'));
    }

    /**
     * Related plan model
     * @return BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Activate the key
     * @return bool
     */
    public function activate()
    {
        return $this->update(['activated_at' => now()]);
    }

    /**
     * Deactivate the key
     * @return bool
     */
    public function deactivate()
    {
        return $this->update(['activated_at' => null]);
    }

    /**
     * Filter by plan ID
     * @param Builder $query
     * @param int $planId
     * @return Builder
     */
    public function scopeOfPlan(Builder $query, $planId)
    {
        return $query->where('plan_id', $planId);
    }

    /**
     * Filter by domain
     * @param Builder $query
     * @param $domain
     * @return Builder
     */
    public function scopeOfDomain(Builder $query, $domain)
    {
        return $query->where('domain', $domain);
    }

    /**
     * Filter by domain using LIKE
     * @param Builder $query
     * @param $domain
     * @return Builder
     */
    public function scopeOfDomainLike(Builder $query, $domain)
    {
        return $query->where('domain', 'like', "%$domain%");
    }

    /**
     * Filter by activated property
     * @param Builder $query
     * @return Builder
     */
    public function scopeActivated(Builder $query)
    {
        return $query->whereNotNull('activated_at');
    }

    /**
     * Filter by valid status
     * @param Builder $query
     * @return mixed
     */
    public function scopeValid(Builder $query)
    {
        return $query->join('plans', 'plans.id', '=', 'keys.plan_id')
            ->whereRaw('keys.status > 0')
            ->whereRaw('keys.activated_at IS NOT NULL')
            ->whereRaw('plans.status > 0')
            ->whereRaw('(plans.lifetime IS NULL OR keys.activated_at >= NOW() - INTERVAL plans.lifetime SECOND)');
    }

    /**
     * Whether the key is valid
     * @return bool
     */
    public function isValid()
    {
        return $this->isActive()
            && $this->isActivated()
            && $this->plan instanceof Plan
            && $this->plan->isActive()
            && !$this->isExpired();
    }

    /**
     * Whether the key can be activated
     * @return bool
     */
    public function canActivate()
    {
        return $this->isActive()
            && !$this->isActivated()
            && $this->plan instanceof Plan
            && $this->plan->isActive()
            && $this->user instanceof User;
    }

    /**
     * Whether the key already expired
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->plan->isExpirable()
            && $this->activated_at->lt(now()->subSeconds($this->plan->lifetime));
    }

}
