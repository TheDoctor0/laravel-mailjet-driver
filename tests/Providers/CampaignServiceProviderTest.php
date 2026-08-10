<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Providers;

use Mailjet\LaravelMailjet\Services\CampaignService;
use Mailjet\LaravelMailjet\Contracts\CampaignContract;
use Mailjet\LaravelMailjet\Providers\CampaignServiceProvider;

class CampaignServiceProviderTest extends ServiceProviderTestCase
{
    protected static function providerClass(): string
    {
        return CampaignServiceProvider::class;
    }

    protected static function contractClass(): string
    {
        return CampaignContract::class;
    }

    protected static function serviceClass(): string
    {
        return CampaignService::class;
    }
}
