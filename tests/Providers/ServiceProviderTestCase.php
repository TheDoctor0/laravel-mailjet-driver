<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Providers;

use Orchestra\Testbench\TestCase;
use Mailjet\LaravelMailjet\Exception\MailjetException;

/**
 * Base test case for the deferred, contract-binding service providers.
 */
abstract class ServiceProviderTestCase extends TestCase
{
    /**
     * The service provider class under test.
     */
    abstract protected static function providerClass(): string;

    /**
     * The contract the provider binds.
     */
    abstract protected static function contractClass(): string;

    /**
     * The concrete service the contract must resolve to.
     */
    abstract protected static function serviceClass(): string;

    protected function getPackageProviders($app): array
    {
        return [static::providerClass()];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('services.mailjet.key', 'ABC123456');
        $app['config']->set('services.mailjet.secret', 'DEF789101');
        $app['config']->set('services.mailjet.common.call', false);
        $app['config']->set('services.mailjet.common.options', []);
    }

    public function testContractResolvesToConcreteService(): void
    {
        $this->assertInstanceOf(
            static::serviceClass(),
            $this->app->make(static::contractClass())
        );
    }

    public function testResolutionThrowsClearExceptionWithoutCredentials(): void
    {
        $this->app['config']->set('services.mailjet', []);

        $this->expectException(MailjetException::class);
        $this->expectExceptionMessage('Mailjet API credentials are not configured');

        $this->app->make(static::contractClass());
    }

    public function testProvidesContract(): void
    {
        $providerClass = static::providerClass();
        $provider = new $providerClass($this->app);

        $this->assertSame([static::contractClass()], $provider->provides());
    }

    public function testBootDoesNothing(): void
    {
        $providerClass = static::providerClass();
        $provider = new $providerClass($this->app);

        $this->assertNull($provider->boot());
    }
}
