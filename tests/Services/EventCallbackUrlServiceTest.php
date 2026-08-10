<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Services;

use Mailjet\Resources;
use Mailjet\LaravelMailjet\Model\EventCallbackUrl;
use Mailjet\LaravelMailjet\Services\MailjetService;
use Mailjet\LaravelMailjet\Services\EventCallbackUrlService;

class EventCallbackUrlServiceTest extends ServiceTestCase
{
    protected function makeService(MailjetService $mailjet): object
    {
        return new EventCallbackUrlService($mailjet);
    }

    public static function calls(): array
    {
        $url = new EventCallbackUrl('https://example.com/webhook');

        return [
            'getAll' => [
                'getAll', [],
                'get', [Resources::$Eventcallbackurl],
                'EventCallbackUrlService:getAll() failed',
            ],
            'get' => [
                'get', ['1'],
                'get', [Resources::$Eventcallbackurl, ['id' => '1']],
                'EventCallbackUrlService:get() failed',
            ],
            'create' => [
                'create', [$url],
                'post', [Resources::$Eventcallbackurl, ['body' => $url->format()]],
                'EventCallbackUrlService:create() failed',
            ],
            'update' => [
                'update', ['1', $url],
                'put', [Resources::$Eventcallbackurl, ['id' => '1', 'body' => $url->format()]],
                'EventCallbackUrlService:update() failed',
            ],
            'delete' => [
                'delete', ['1'],
                'delete', [Resources::$Eventcallbackurl, ['id' => '1']],
                'EventCallbackUrlService:delete() failed',
            ],
        ];
    }
}
