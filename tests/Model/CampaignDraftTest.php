<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Model;

use PHPUnit\Framework\TestCase;
use Mailjet\LaravelMailjet\Model\CampaignDraft;

class CampaignDraftTest extends TestCase
{
    public function testFormatContainsAllRequiredKeysAndOptionalProperties(): void
    {
        $draft = new CampaignDraft(
            'en_US',
            'Mailjet',
            'sender@mailjet.com',
            'Subject',
            '1',
            ['Title' => 'Newsletter']
        );

        $this->assertSame([
            CampaignDraft::LOCALE_KEY => 'en_US',
            CampaignDraft::SENDER_KEY => 'Mailjet',
            CampaignDraft::SENDER_EMAIL_KEY => 'sender@mailjet.com',
            CampaignDraft::SUBJECT_KEY => 'Subject',
            CampaignDraft::CONTACT_LIST_ID_KEY => '1',
            'Title' => 'Newsletter',
        ], $draft->format());
    }

    public function testContentAccessors(): void
    {
        $draft = new CampaignDraft('en_US', 'Mailjet', 'sender@mailjet.com', 'Subject', '1');

        $this->assertNull($draft->getContent());

        $content = ['Html-part' => '<p>Hello</p>', 'Text-part' => 'Hello'];

        $this->assertSame($draft, $draft->setContent($content));
        $this->assertSame($content, $draft->getContent());
    }

    public function testIdAccessors(): void
    {
        $draft = new CampaignDraft('en_US', 'Mailjet', 'sender@mailjet.com', 'Subject', '1');

        $this->assertNull($draft->getId());
        $this->assertSame($draft, $draft->setId('42'));
        $this->assertSame('42', $draft->getId());
    }
}
