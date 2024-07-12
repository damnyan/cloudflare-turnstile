<?php

namespace Dmn\CloudflareTurnstile;

use Illuminate\Support\Facades\Http;

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
     * @return boolean
     */
    public function siteVerify(string $response, string $ipAddress): bool
    {
        $response = Http::baseUrl(url: $this->config['base_uri'])
            ->post(
                url: 'siteverify',
                data: [
                    'secret' => $this->config['secret'],
                    'response' => $response,
                    'remote_ip' => $ipAddress,
                ],
            );

        if ($response->failed()) {
            return false;
        }

        return $response->json(key: 'success');
    }
}
