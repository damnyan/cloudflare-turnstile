<?php

namespace Dmn\CloudflareTurnstile;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Service implements Turnstile
{
    /**
     * Construct
     *
     * @param array $config
     */
    public function __construct(protected array $config)
    {
    }

    /**
     * Site verify
     *
     * @param string $response
     * @param string $ipAddress
     * @param string|null $idempotencyKey
     * @return boolean
     */
    public function siteVerify(string $response, string $ipAddress, ?string $idempotencyKey = null): bool
    {
        if (empty($idempotencyKey)) {
            $idempotencyKey = Str::orderedUuid();
        }

        $response = Http::baseUrl(url: $this->config['base_uri'])
            ->post(
                url: 'siteverify',
                data: [
                    'secret' => $this->config['secret'],
                    'response' => $response,
                    'remote_ip' => $ipAddress,
                    'idempotency_key' => $idempotencyKey,
                ],
            );

        if ($response->failed()) {
            return false;
        }

        return $response->json(key: 'success');
    }
}
