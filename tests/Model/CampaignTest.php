<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Model;

use PHPUnit\Framework\TestCase;
use Mailjet\LaravelMailjet\Model\Campaign;

class CampaignTest extends TestCase
{
    public function testFormatContainsFromEmailAndOptionalProperties(): void
    {
        $campaign = new Campaign('from@mailjet.com', ['Title' => 'Newsletter']);

        $this->assertSame([
            Campaign::FROM_EMAIL_KEY => 'from@mailjet.com',
            'Title' => 'Newsletter',
        ], $campaign->format());
    }

    public function testOptionalPropertiesCanBeManaged(): void
    {
        $campaign = new Campaign('from@mailjet.com', []);

        $this->assertSame([], $campaign->getOptionalProperties());

        $this->assertSame(
            ['Title' => 'A'],
            $campaign->setOptionalProperties(['Title' => 'A'])
        );

        $this->assertSame(
            ['Title' => 'A', ['Locale' => 'en_US']],
            $campaign->addOptionalProperty(['Locale' => 'en_US'])
        );

        $this->assertSame(
            ['Title' => 'A'],
            $campaign->removeOptionalProperty(['Locale' => 'en_US'])
        );
    }
}
