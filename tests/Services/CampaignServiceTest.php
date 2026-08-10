<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Services;

use Mailjet\Resources;
use Mailjet\LaravelMailjet\Model\Campaign;
use Mailjet\LaravelMailjet\Services\MailjetService;
use Mailjet\LaravelMailjet\Services\CampaignService;

class CampaignServiceTest extends ServiceTestCase
{
    protected function makeService(MailjetService $mailjet): object
    {
        return new CampaignService($mailjet);
    }

    public static function calls(): array
    {
        $campaign = new Campaign('from@mailjet.com', ['Title' => 'Newsletter']);

        return [
            'getAllCampaigns' => [
                'getAllCampaigns', [['Limit' => 10]],
                'get', [Resources::$Campaign, ['filters' => ['Limit' => 10]]],
                'CampaignService:getAllCampaigns() failed',
            ],
            'findByCampaignId' => [
                'findByCampaignId', ['1'],
                'get', [Resources::$Campaign, ['id' => '1']],
                'CampaignService:findByCampaignId() failed',
            ],
            'findByNewsletterId' => [
                'findByNewsletterId', ['2'],
                'get', [Resources::$Campaign, ['mj.nl' => '2']],
                'CampaignService:findByNewsletterId() failed',
            ],
            'updateCampaign' => [
                'updateCampaign', ['1', $campaign],
                'put', [Resources::$Campaign, ['id' => '1', 'body' => $campaign->format()]],
                'CampaignService:updateCampaign() failed',
            ],
        ];
    }
}
