<?php

namespace Sribna\Licensor\Events;

use Sribna\Licensor\Models\Key;

/**
 * Class KeyVerified
 * @package Sribna\Licensor\Events
 */
class KeyVerified
{
    /**
     * @var Key
     */
    public $key;

    /**
     * KeyVerified constructor.
     * @param Key $key
     */
    public function __construct(Key $key)
    {
        $this->key = $key;
    }

}
