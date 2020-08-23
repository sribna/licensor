<?php

namespace Sribna\Licensor\Events;

use Sribna\Licensor\Models\Key;

/**
 * Class KeyNotVerified
 * @package Sribna\Licensor\Events
 */
class KeyNotVerified
{
    /**
     * @var Key
     */
    public $key;

    /**
     * KeyNotVerified constructor.
     * @param Key $key
     */
    public function __construct(Key $key)
    {
        $this->key = $key;
    }

}
