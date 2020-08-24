<?php

namespace Sribna\Licensor\Events;

use GuzzleHttp\TransferStats;
use Psr\Http\Message\ResponseInterface;
use Sribna\Licensor\Models\Key;
use Sribna\Licensor\Models\Secret;

/**
 * Class PrivateKeySent
 * @package Sribna\Licensor\Events
 */
class PrivateKeySent
{
    /**
     * @var Key
     */
    public $key;

    /**
     * @var Secret
     */
    public $secret;

    /**
     * @var string
     */
    public $privateKey;

    /**
     * @var ResponseInterface
     */
    public $response;

    /**
     * @var TransferStats
     */
    public $transfer;

    /**
     * PrivateKeySent constructor.
     * @param Key $key
     * @param Secret $secret
     * @param string $privateKey
     * @param ResponseInterface $response
     * @param TransferStats $transfer
     */
    public function __construct(
        Key $key,
        Secret $secret,
        string $privateKey,
        ResponseInterface $response,
        TransferStats $transfer)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->response = $response;
        $this->transfer = $transfer;
        $this->privateKey = $privateKey;
    }

}
