<?php

namespace Sribna\Licensor\Events;

use Sribna\Licensor\Models\Key;

/**
 * Class KeyNotActivated
 * @package Sribna\Licensor\Events
 */
class KeyNotActivated
{
    /**
     * @var Key
     */
    public $key;

    /**
     * KeyNotActivated constructor.
     * @param Key $key
     */
    public function __construct(Key $key)
    {
        $this->key = $key;
    }

}
