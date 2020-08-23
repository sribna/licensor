<?php

namespace Sribna\Licensor\Events;

use Sribna\Licensor\Models\Key;

/**
 * Class KeyIssued
 * @package Sribna\Licensor\Events
 */
class KeyIssued
{
    /**
     * @var Key
     */
    public $key;

    /**
     * KeyIssued constructor.
     * @param Key $key
     */
    public function __construct(Key $key)
    {
        $this->key = $key;
    }

}
