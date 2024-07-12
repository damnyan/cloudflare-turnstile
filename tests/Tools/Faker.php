<?php

namespace Dmn\CloudflareTurnstile\Tests\Tools;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

trait Faker
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
            . '/Responses/'
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

