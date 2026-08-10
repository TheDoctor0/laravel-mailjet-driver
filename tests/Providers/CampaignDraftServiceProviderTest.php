<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Providers;

use Mailjet\LaravelMailjet\Services\CampaignDraftService;
use Mailjet\LaravelMailjet\Contracts\CampaignDraftContract;
use Mailjet\LaravelMailjet\Providers\CampaignDraftServiceProvider;

class CampaignDraftServiceProviderTest extends ServiceProviderTestCase
{
    protected static function providerClass(): string
    {
        return CampaignDraftServiceProvider::class;
    }

    protected static function contractClass(): string
    {
        return CampaignDraftContract::class;
    }

    protected static function serviceClass(): string
    {
        return CampaignDraftService::class;
    }
}
