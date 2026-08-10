<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Providers;

use Mailjet\LaravelMailjet\Services\ContactsListService;
use Mailjet\LaravelMailjet\Contracts\ContactsListContract;
use Mailjet\LaravelMailjet\Providers\ContactsListServiceProvider;

class ContactsListServiceProviderTest extends ServiceProviderTestCase
{
    protected static function providerClass(): string
    {
        return ContactsListServiceProvider::class;
    }

    protected static function contractClass(): string
    {
        return ContactsListContract::class;
    }

    protected static function serviceClass(): string
    {
        return ContactsListService::class;
    }
}
