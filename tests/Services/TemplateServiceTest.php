<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Services;

use Mailjet\Resources;
use Mailjet\LaravelMailjet\Model\Template;
use Mailjet\LaravelMailjet\Services\MailjetService;
use Mailjet\LaravelMailjet\Services\TemplateService;

class TemplateServiceTest extends ServiceTestCase
{
    protected function makeService(MailjetService $mailjet): object
    {
        return new TemplateService($mailjet);
    }

    public static function calls(): array
    {
        $template = new Template('my-template');

        return [
            'getAll' => [
                'getAll', [['Limit' => 5]],
                'get', [Resources::$Template, ['filters' => ['Limit' => 5]]],
                'TemplateService:getAll() failed',
            ],
            'get' => [
                'get', ['1'],
                'get', [Resources::$Template, ['id' => '1']],
                'TemplateService:get() failed',
            ],
            'create' => [
                'create', [$template],
                'post', [Resources::$Template, ['body' => $template->format()]],
                'TemplateService:create() failed',
            ],
            'update' => [
                'update', ['1', $template],
                'put', [Resources::$Template, ['id' => '1', 'body' => $template->format()]],
                'TemplateService:update() failed',
            ],
            'delete' => [
                'delete', ['1'],
                'delete', [Resources::$Template, ['id' => '1']],
                'TemplateService:delete() failed',
            ],
            'getDetailContent' => [
                'getDetailContent', ['1'],
                'get', [Resources::$TemplateDetailcontent, ['id' => '1']],
                'TemplateService:getDetailContent failed',
            ],
            'createDetailContent' => [
                'createDetailContent', ['1', ['Html-part' => '<p>Hi</p>']],
                'post', [Resources::$TemplateDetailcontent, ['id' => '1', 'body' => ['Html-part' => '<p>Hi</p>']]],
                'TemplateService:createDetailContent failed',
            ],
            'deleteDetailContent' => [
                'deleteDetailContent', ['1'],
                'post', [Resources::$TemplateDetailcontent, ['id' => '1', 'body' => null]],
                'TemplateService:createDetailContent failed',
            ],
        ];
    }
}
