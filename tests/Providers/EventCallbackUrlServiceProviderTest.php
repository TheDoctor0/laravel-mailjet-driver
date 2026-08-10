<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Providers;

use Mailjet\LaravelMailjet\Services\EventCallbackUrlService;
use Mailjet\LaravelMailjet\Contracts\EventCallbackUrlContract;
use Mailjet\LaravelMailjet\Providers\EventCallbackUrlServiceProvider;

class EventCallbackUrlServiceProviderTest extends ServiceProviderTestCase
{
    protected static function providerClass(): string
    {
        return EventCallbackUrlServiceProvider::class;
    }

    protected static function contractClass(): string
    {
        return EventCallbackUrlContract::class;
    }

    protected static function serviceClass(): string
    {
        return EventCallbackUrlService::class;
    }
}
