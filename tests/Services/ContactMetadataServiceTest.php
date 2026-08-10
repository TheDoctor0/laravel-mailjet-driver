<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Services;

use Mailjet\Resources;
use Mailjet\LaravelMailjet\Model\ContactMetadata;
use Mailjet\LaravelMailjet\Services\MailjetService;
use Mailjet\LaravelMailjet\Services\ContactMetadataService;

class ContactMetadataServiceTest extends ServiceTestCase
{
    protected function makeService(MailjetService $mailjet): object
    {
        return new ContactMetadataService($mailjet);
    }

    public static function calls(): array
    {
        $metadata = new ContactMetadata('age', ContactMetadata::DATATYPE_INT);

        return [
            'getAll' => [
                'getAll', [],
                'get', [Resources::$Contactmetadata],
                'ContactMetadataService:getAll() failed',
            ],
            'get' => [
                'get', ['1'],
                'get', [Resources::$Contactmetadata, ['id' => '1']],
                'ContactMetadataService:get() failed',
            ],
            'create' => [
                'create', [$metadata],
                'post', [Resources::$Contactmetadata, ['body' => $metadata->format()]],
                'ContactMetadataService:create() failed',
            ],
            'update' => [
                'update', ['1', $metadata],
                'put', [Resources::$Contactmetadata, ['id' => '1', 'body' => $metadata->format()]],
                'ContactMetadataService:update() failed',
            ],
            'delete' => [
                'delete', ['1'],
                'delete', [Resources::$Contactmetadata, ['id' => '1']],
                'ContactMetadataService:delete() failed',
            ],
        ];
    }
}
