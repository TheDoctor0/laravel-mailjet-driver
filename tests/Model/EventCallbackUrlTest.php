<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Model;

use RuntimeException;
use PHPUnit\Framework\TestCase;
use Mailjet\LaravelMailjet\Model\EventCallbackUrl;

class EventCallbackUrlTest extends TestCase
{
    public function testFormatWithDefaults(): void
    {
        $url = new EventCallbackUrl('https://example.com/webhook');

        $this->assertSame([
            'Url' => 'https://example.com/webhook',
            'EventType' => EventCallbackUrl::EVENT_TYPE_OPEN,
            'IsBackup' => false,
            'Status' => EventCallbackUrl::EVENT_STATUS_ALIVE,
            'Version' => 1,
        ], $url->format());
    }

    public function testFormatForcesVersionTwoForGroupedEventsAndIncludesApiKeyId(): void
    {
        $url = new EventCallbackUrl(
            'https://example.com/webhook',
            EventCallbackUrl::EVENT_TYPE_CLICK,
            true,
            true,
            EventCallbackUrl::EVENT_STATUS_DEAD,
            1,
            '12345'
        );

        $this->assertSame([
            'Url' => 'https://example.com/webhook',
            'EventType' => EventCallbackUrl::EVENT_TYPE_CLICK,
            'IsBackup' => true,
            'Status' => EventCallbackUrl::EVENT_STATUS_DEAD,
            'Version' => 2,
            'APIKeyID' => '12345',
        ], $url->format());
    }

    public function testConstructorRejectsInvalidEventType(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('invalid: is not a valid event type.');

        new EventCallbackUrl('https://example.com/webhook', 'invalid');
    }

    public function testConstructorRejectsInvalidEventStatus(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('invalid: is not a valid event status.');

        new EventCallbackUrl(
            'https://example.com/webhook',
            EventCallbackUrl::EVENT_TYPE_OPEN,
            false,
            false,
            'invalid'
        );
    }
}
