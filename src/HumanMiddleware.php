<?php

namespace Dmn\CloudflareTurnstile;

use Closure;
use Dmn\CloudflareTurnstile\NotHumanException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HumanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $headerKey = config(key: 'cloudflare.turnstile.token_header_key');

        $token = $request->headers->get(key: $headerKey);

        if (empty($token)) {
            $this->failed();
        }

        $verified = $this->turnstile()->siteVerify(
            response: $token,
            ipAddress: $request->ip()
        );

        if (!$verified) {
            $this->failed();
        }

        return $next($request);
    }

    /**
     * Turnstil
     *
     * @return Turnstile
     */
    protected function turnstile(): Turnstile
    {
        return app(abstract: Turnstile::class);
    }

    /**
     * Failed
     * 
     * @throws NotHumanException
     *
     * @return void
     */
    public function failed(): void
    {
        throw new NotHumanException();
    }
}
