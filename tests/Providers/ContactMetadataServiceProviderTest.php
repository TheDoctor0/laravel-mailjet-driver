<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Providers;

use Mailjet\LaravelMailjet\Services\ContactMetadataService;
use Mailjet\LaravelMailjet\Contracts\ContactMetadataContract;
use Mailjet\LaravelMailjet\Providers\ContactMetadataServiceProvider;

class ContactMetadataServiceProviderTest extends ServiceProviderTestCase
{
    protected static function providerClass(): string
    {
        return ContactMetadataServiceProvider::class;
    }

    protected static function contractClass(): string
    {
        return ContactMetadataContract::class;
    }

    protected static function serviceClass(): string
    {
        return ContactMetadataService::class;
    }
}
