<?php

namespace Dmn\CloudflareTurnstile;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

trait TurnstileFaker
{
    /**
     * Cloudflare turnstile fake
     *
     * @param boolean $success
     * @param int $status
     *
     * @return void
     */
    public function cloudfareTurnstileFake(
        bool $success = true,
        int $status = Response::HTTP_OK
    ): void {
        $file = __DIR__
            . '/../tests/Tools/Responses/'
            . (($success) ? 'success' : 'failed')
            . '.json';
        $baseUri = config(key: 'cloudflare.turnstile.base_url');
        Http::fake([
            $baseUri . '/*' => Http::response(
                    body: file_get_contents(filename: $file),
                    status: $status,
                ),
        ]);
    }
}

