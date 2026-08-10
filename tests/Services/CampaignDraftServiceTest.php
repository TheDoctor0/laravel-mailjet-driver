<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Services;

use Mailjet\Resources;
use Mailjet\LaravelMailjet\Model\CampaignDraft;
use Mailjet\LaravelMailjet\Services\MailjetService;
use Mailjet\LaravelMailjet\Services\CampaignDraftService;

class CampaignDraftServiceTest extends ServiceTestCase
{
    protected function makeService(MailjetService $mailjet): object
    {
        return new CampaignDraftService($mailjet);
    }

    public static function calls(): array
    {
        $draft = new CampaignDraft('en_US', 'Mailjet', 'sender@mailjet.com', 'Subject', '1');

        return [
            'getAllCampaignDrafts' => [
                'getAllCampaignDrafts', [['Limit' => 10]],
                'get', [Resources::$Campaigndraft, ['filters' => ['Limit' => 10]]],
                'CampaignDraftService :getAllCampaignDrafts() failed',
            ],
            'findByCampaignDraftId' => [
                'findByCampaignDraftId', ['1'],
                'get', [Resources::$Campaigndraft, ['id' => '1']],
                'CampaignDraftService:findByCampaignDraftId() failed',
            ],
            'create' => [
                'create', [$draft],
                'post', [Resources::$Campaigndraft, ['body' => $draft->format()]],
                'CampaignDraftService:create() failed',
            ],
            'update' => [
                'update', ['1', $draft],
                'put', [Resources::$Campaigndraft, ['id' => '1', 'body' => $draft->format()]],
                'CampaignDraftService:update() failed',
            ],
            'getDetailContent' => [
                'getDetailContent', ['1'],
                'get', [Resources::$CampaigndraftDetailcontent, ['id' => '1']],
                'CampaignDraftService:getDetailContent failed',
            ],
            'createDetailContent' => [
                'createDetailContent', ['1', ['Html-part' => '<p>Hi</p>']],
                'post', [Resources::$CampaigndraftDetailcontent, ['id' => '1', 'body' => ['Html-part' => '<p>Hi</p>']]],
                'CampaignDraftService:createDetailContent failed',
            ],
            'getSchedule' => [
                'getSchedule', ['1'],
                'get', [Resources::$CampaigndraftSchedule, ['id' => '1']],
                'CampaignDraftService:getSchedule failed',
            ],
            'scheduleCampaign' => [
                'scheduleCampaign', ['1', '2026-01-01T00:00:00+00:00'],
                'post', [Resources::$CampaigndraftSchedule, ['id' => '1', 'body' => '2026-01-01T00:00:00+00:00']],
                'CampaignDraftService:scheduleCampaign failed',
            ],
            'updateCampaignSchedule' => [
                'updateCampaignSchedule', ['1', '2026-01-02T00:00:00+00:00'],
                'put', [Resources::$CampaigndraftSchedule, ['id' => '1', 'body' => '2026-01-02T00:00:00+00:00']],
                'CampaignDraftService:updateCampaignSchedule failed',
            ],
            'removeSchedule' => [
                'removeSchedule', ['1'],
                'delete', [Resources::$CampaigndraftSchedule, ['id' => '1']],
                'CampaignDraftService:removeSchedule failed',
            ],
            'sendCampaign' => [
                'sendCampaign', ['1'],
                'post', [Resources::$CampaigndraftSend, ['id' => '1']],
                'CampaignDraftService:sendCampaign failed',
            ],
            'getCampaignStatus' => [
                'getCampaignStatus', ['1'],
                'get', [Resources::$CampaigndraftStatus, ['id' => '1']],
                'CampaignDraftService:getCampaignStatus failed',
            ],
            'testCampaign' => [
                'testCampaign', ['1', [['Email' => 'to@mailjet.com']]],
                'post', [Resources::$CampaigndraftTest, ['id' => '1', 'body' => [['Email' => 'to@mailjet.com']]]],
                'CampaignDraftService:testCampaign failed',
            ],
        ];
    }
}
