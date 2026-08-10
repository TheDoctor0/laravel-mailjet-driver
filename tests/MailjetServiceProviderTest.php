<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests;

use Orchestra\Testbench\TestCase;
use Mailjet\LaravelMailjet\MailjetServiceProvider;
use Mailjet\LaravelMailjet\Exception\MailjetException;

class MailjetServiceProviderTest extends TestCase
{
    public function testItThrowsClearExceptionWhenCredentialsAreMissing(): void
    {
        $this->expectException(MailjetException::class);
        $this->expectExceptionMessage('Mailjet API credentials are not configured');

        $this->app->make('Mailjet');
    }

    protected function getPackageProviders($app): array
    {
        return [MailjetServiceProvider::class];
    }
}
