<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Model;

use PHPUnit\Framework\TestCase;
use Mailjet\LaravelMailjet\Model\Template;

class TemplateTest extends TestCase
{
    public function testFormatContainsNameAndOptionalProperties(): void
    {
        $template = new Template('my-template', ['Purposes' => ['transactional']]);

        $this->assertSame([
            Template::NAME_KEY => 'my-template',
            'Purposes' => ['transactional'],
        ], $template->format());
    }

    public function testContentAccessors(): void
    {
        $template = new Template('my-template');

        $this->assertNull($template->getContent());

        $content = ['Html-part' => '<p>Hi</p>'];

        $this->assertSame($template, $template->setContent($content));
        $this->assertSame($content, $template->getContent());
    }

    public function testIdAccessors(): void
    {
        $template = new Template('my-template');

        $this->assertNull($template->getId());
        $this->assertSame($template, $template->setId('7'));
        $this->assertSame('7', $template->getId());
    }
}
