<?php

namespace Dmn\CloudflareTurnstile;


interface Turnstile
{

    /**
     * Site verify
     *
     * @param string $response
     * @param string $ipAddress
     *
     * @return boolean
     */
    public function siteVerify(string $response, string $ipAddress): bool;
}
