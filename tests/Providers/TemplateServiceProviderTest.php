<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Providers;

use Mailjet\LaravelMailjet\Services\TemplateService;
use Mailjet\LaravelMailjet\Contracts\TemplateServiceContract;
use Mailjet\LaravelMailjet\Providers\TemplateServiceProvider;

class TemplateServiceProviderTest extends ServiceProviderTestCase
{
    protected static function providerClass(): string
    {
        return TemplateServiceProvider::class;
    }

    protected static function contractClass(): string
    {
        return TemplateServiceContract::class;
    }

    protected static function serviceClass(): string
    {
        return TemplateService::class;
    }
}
