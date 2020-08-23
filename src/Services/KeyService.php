<?php

namespace Sribna\Licensor\Services;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Sribna\Licensor\Events\KeyActivated;
use Sribna\Licensor\Events\KeyIssued;
use Sribna\Licensor\Events\KeyNotActivated;
use Sribna\Licensor\Events\KeyNotVerified;
use Sribna\Licensor\Events\KeyVerified;
use Sribna\Licensor\Events\PrivateKeySent;
use Sribna\Licensor\Exceptions\KeyActivationException;
use Sribna\Licensor\Exceptions\KeyValidationException;
use Sribna\Licensor\Exceptions\KeyVerificationException;
use Sribna\Licensor\Models\Key;
use Sribna\Licensor\Models\Secret;
use Throwable;

/**
 * Class KeyService
 * @package Sribna\Licensor\Services
 */
class KeyService
{

    /**
     * @var Client
     */
    protected $http;

    /**
     * KeyService constructor.
     * @param Client $http
     */
    public function __construct(Client $http)
    {
        $this->http = $http;
    }

    /**
     * Generate a public key
     * @param int $length
     * @return string
     */
    public function generate(int $length = 32): string
    {
        return Str::random($length);
    }

    /**
     * Issue a new public key
     * @param int $userId
     * @param int $planId
     * @param string $domain
     * @param string|null $key
     * @return Key
     * @throws Throwable
     */
    public function issue(int $userId, int $planId, string $domain, string $key = null): Key
    {
        if (Key::where('domain', $domain)
            ->where('user_id', '<>', $userId)
            ->exists()) {
            throw new RuntimeException('Domain already taken by another user');
        }

        $key = Key::create([
            'status' => true,
            'domain' => $domain,
            'plan_id' => $planId,
            'user_id' => $userId,
            'id' => $key ?: $this->generate(),
        ]);

        event(new KeyIssued($key));
        return $key;
    }

    /**
     * Validates a private key
     * @param string $privateKey
     * @return array
     */
    public function validate(string $privateKey): array
    {
        [$hash, $publicKey, $domain] = $this->parse($privateKey);

        /** @var Key $key */
        if (!($key = Key::find($publicKey))) {
            throw new KeyValidationException('Key does not exist');
        }

        if (!$key->isActive()) {
            throw new KeyValidationException('Key is not active');
        }

        if ($domain !== $key->domain) {
            throw new KeyValidationException('Key domain mismatch');
        }

        if (!($secret = $this->findSecret($key, $hash))) {
            throw new KeyValidationException('No matching secret found');
        }

        return [$key, $secret];
    }

    /**
     * Activate licensee keys
     * @param Key $key
     * @return bool
     */
    public function activate(Key $key): bool
    {
        try {

            if (!$key->canActivate()) {
                throw new KeyActivationException('Key cannot be activated');
            }

            if (!$key->activate()) {
                throw new KeyActivationException('Failed to activate');
            }

            event(new KeyActivated($key));
            return true;

        } catch (KeyActivationException $exception) {
            event(new KeyNotActivated($key));
            throw $exception;
        }
    }

    /**
     * Route callback to verify licensee keys
     * @param Key $key
     * @return bool
     */
    public function verify(Key $key)
    {
        try {

            if (!$key->isValid()) {
                throw new KeyVerificationException('Key is not valid');
            }

            if (!$key->user instanceof User) {
                throw new KeyVerificationException('Invalid key user');
            }

            event(new KeyVerified($key));
            return true;

        } catch (KeyVerificationException $exception) {
            event(new KeyNotVerified($key));
            throw $exception;
        }
    }

    /**
     * Send the private key to the licensee
     * @param Key $key
     * @param Secret $secret
     * @return ResponseInterface
     */
    public function callback(Key $key, Secret $secret)
    {
        $privateKey = $this->build($key, $secret);

        $response = $this->http->post($this->getCallbackUrl($key->domain), [
            'body' => $privateKey
        ]);

        event(new PrivateKeySent($key, $secret, $privateKey, $response));
        return $response;
    }

    /**
     * Finds a matching passphrase for the key
     * @param Key $key
     * @param string $hash
     * @return Secret|null
     */
    public function findSecret(Key $key, string $hash): ?Secret
    {
        /** @var Secret $secret */
        foreach (Secret::active()->get() as $secret) {
            if (hash_equals($this->hash($key, $secret), $hash)) {
                return $secret;
            }
        }

        return null;
    }

    /**
     * Returns the callback URL to which the private key will be sent
     * @param string $domain
     * @return string
     */
    public function getCallbackUrl(string $domain)
    {
        return "http://$domain/" . config('licensor.licensee_callback_path');
    }

    /**
     * Parse private key into array
     * @param string $privateKey
     * @return array
     */
    public function parse($privateKey): array
    {
        if (!$privateKey || !is_string($privateKey)) {
            throw new KeyValidationException('Invalid private key value');
        }

        if (($decoded = base64_decode($privateKey)) === false) {
            throw new KeyValidationException('Failed to decode base64 encoded private key');
        }

        if (count(($parts = array_filter(explode('|', $decoded)))) !== 3) {
            throw new KeyValidationException('Wrong number of private key parts');
        }

        return $parts;
    }

    /**
     * Hash key - secret pair
     * @param Key $key
     * @param Secret $secret
     * @return string
     */
    public function hash(Key $key, Secret $secret): string
    {
        return md5("{$secret->id}{$key->id}|{$key->domain}");
    }

    /**
     * Build a private key
     * @param Key $key
     * @param Secret $secret
     * @return string
     */
    public function build(Key $key, Secret $secret): string
    {
        $jsonSettings = json_encode($this->getSettings($key));
        return base64_encode(md5($jsonSettings . $secret->id) . "|$jsonSettings");
    }

    /**
     * Private key settings
     * @param Key $key
     * @return array
     */
    public function getSettings(Key $key): array
    {
        return [
            'key' => $key->id,
            'domain' => $key->domain,
            'shutdown_offset' => config('licensor.key_shutdown_time_offset'),
            'expires_at' => time() + config('licensor.key_expiration_time_offset')
        ];
    }

}
