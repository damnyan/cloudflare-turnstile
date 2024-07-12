<?php

namespace Dmn\CloudflareTurnstile\Tests;

use Dmn\CloudflareTurnstile\HumanMiddleware;
use Dmn\CloudflareTurnstile\NotHumanException;
use Dmn\CloudflareTurnstile\ServiceProvider;
use Dmn\CloudflareTurnstile\Tests\Tools\Faker;
use Dmn\CloudflareTurnstile\Turnstile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Orchestra\Testbench\TestCase;

class VerificationTest extends TestCase
{
    use Faker;

    /**
     * @inheritDoc
     */
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    /**
     * @inheritDoc
     */
    protected function getEnvironmentSetUp($app)
    {
        $config = require __DIR__ . '/../config/config.php';
        $app['config']->set('cloudflare', $config);
    }

    /**
     * Service
     *
     * @return Turnstile
     */
    protected function service(): Turnstile
    {
        return $this->app->make(abstract: Turnstile::class);
    }

    /**
     * @return void
     */
    public function testSuccess(): void
    {
        $this->cloudfareTurnstileFake();
        $service = $this->service();
        $verified = $service->siteVerify(
            response: '123',
            ipAddress: '123.123.12.123',
        );

        $this->assertTrue(condition: $verified);
    }

    /**
     * @return void
     */
    public function testFailed(): void
    {
        $this->cloudfareTurnstileFake(success: false);
        $service = $this->service();
        $verified = $service->siteVerify(
            response: '123',
            ipAddress: '123.123.12.123',
        );

        $this->assertFalse(condition: $verified);
    }

    /**
     * @return void
     */
    public function testBadRequest(): void
    {
        $this->cloudfareTurnstileFake(
            success: false,
            status: Response::HTTP_BAD_REQUEST
        );
        $service = $this->service();
        $verified = $service->siteVerify(
            response: '123',
            ipAddress: '123.123.12.123',
        );

        $this->assertFalse(condition: $verified);
    }

    /**
     * @return void
     */
    public function testHumanMiddlewareSucces()
    {
        $headerKey = config(key: 'cloudflare.turnstile.token_header_key');
        $middleware = new HumanMiddleware();
        $request = new Request(
            query: ['123' => '123'],
            server: ['REMOTE_ADDR' => '0.0.0.0']
        );
        $request->headers->set(key: $headerKey, values: '123');
        $this->cloudfareTurnstileFake();
        $response = $middleware->handle(
            request: $request,
            next: fn ($request): Response => new Response(content: 'test'),
        );

        $this->assertEquals(
            expected: Response::HTTP_OK,
            actual: $response->getStatusCode()
        );
    }

    /**
     * @return void
     */
    public function testHumanMiddlewareEmptyFailed()
    {
        $this->expectException(exception: NotHumanException::class);
        $middleware = new HumanMiddleware();
        $request = new Request(
            query: ['123' => '123'],
            server: ['REMOTE_ADDR' => '0.0.0.0']
        );
        $middleware->handle(
            request: $request,
            next: fn ($request): Response => new Response(content: 'test'),
        );
    }

    /**
     * @return void
     */
    public function testHumanMiddlewareInvalidFailed()
    {
        $this->expectException(exception: NotHumanException::class);
        $headerKey = config(key: 'cloudflare.turnstile.token_header_key');
        $middleware = new HumanMiddleware();
        $request = new Request(
            query: ['123' => '123'],
            server: ['REMOTE_ADDR' => '0.0.0.0']
        );
        $request->headers->set(key: $headerKey, values: '123');
        $this->cloudfareTurnstileFake(success: false);
        $middleware->handle(
            request: $request,
            next: fn ($request): Response => new Response(content: 'test'),
        );
    }
}

