<?php

namespace Sribna\Licensor\Models;

use Illuminate\Database\Eloquent\Model;
use Sribna\Licensor\Traits\HasStatusAttribute;

/**
 * Class Feature
 *
 * @property string $id
 * @property boolean $status
 * @property string $title
 * @property string $text
 */
class Feature extends Model
{

    use HasStatusAttribute;

    /**
     * @var bool
     */
    public $timestamps = false;

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
        'title',
        'text'
    ];

    /**
     * The attributes that should be cast.
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];

}
