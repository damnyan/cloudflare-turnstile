<?php

namespace Dmn\CloudflareTurnstile;


interface Turnstile
{

    /**
     * Site verify
     *
     * @param string $response
     * @param string $ipAddress
     * @param string|null $idempotencyKey
     * @return boolean
     */
    public function siteVerify(
        string $response,
        string $ipAddress,
        ?string $idempotencyKey = null
    ): bool;
}
