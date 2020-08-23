<?php

namespace Sribna\Licensor\Events;

use Sribna\Licensor\Models\Key;

/**
 * Class KeyActivated
 * @package Sribna\Licensor\Events
 */
class KeyActivated
{
    /**
     * @var Key
     */
    public $key;

    /**
     * KeyActivated constructor.
     * @param Key $key
     */
    public function __construct(Key $key)
    {
        $this->key = $key;
    }

}
