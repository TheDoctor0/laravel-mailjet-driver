<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Providers;

use Mailjet\LaravelMailjet\Services\MailjetService;
use Mailjet\LaravelMailjet\Contracts\MailjetServiceContract;
use Mailjet\LaravelMailjet\Providers\MailjetClientServiceProvider;

class MailjetClientServiceProviderTest extends ServiceProviderTestCase
{
    protected static function providerClass(): string
    {
        return MailjetClientServiceProvider::class;
    }

    protected static function contractClass(): string
    {
        return MailjetServiceContract::class;
    }

    protected static function serviceClass(): string
    {
        return MailjetService::class;
    }
}
