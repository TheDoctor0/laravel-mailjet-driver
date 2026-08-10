<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Model;

use RuntimeException;
use PHPUnit\Framework\TestCase;
use Mailjet\LaravelMailjet\Model\Contact;

class ContactTest extends TestCase
{
    public function testFormatContainsEmailAndProperties(): void
    {
        $contact = new Contact('test@mailjet.com', ['FirstName' => 'John']);

        $this->assertSame([
            Contact::EMAIL_KEY => 'test@mailjet.com',
            Contact::PROPERTIES_KEY => ['FirstName' => 'John'],
        ], $contact->format());
    }

    public function testFormatKeepsFalsyPropertyValues(): void
    {
        $contact = new Contact('test@mailjet.com', [
            'newsletter_optin' => false,
            'score' => 0,
            'nickname' => '',
            'unused' => null,
        ]);

        $this->assertSame([
            'newsletter_optin' => false,
            'score' => 0,
            'nickname' => '',
        ], $contact->format()[Contact::PROPERTIES_KEY]);
    }

    public function testFormatContainsActionWhenSet(): void
    {
        $contact = new Contact('test@mailjet.com');
        $contact->setAction(Contact::ACTION_ADDNOFORCE);

        $this->assertSame(Contact::ACTION_ADDNOFORCE, $contact->format()[Contact::ACTION_KEY]);
    }

    public function testEmailAccessors(): void
    {
        $contact = new Contact('test@mailjet.com');

        $this->assertSame('test@mailjet.com', $contact->getEmail());
        $this->assertSame($contact, $contact->setEmail('other@mailjet.com'));
        $this->assertSame('other@mailjet.com', $contact->getEmail());
    }

    public function testNameAccessors(): void
    {
        $contact = new Contact('test@mailjet.com');

        $this->assertNull($contact->getName());
        $this->assertSame($contact, $contact->setName('John'));
        $this->assertSame('John', $contact->getName());
    }

    public function testActionAccessors(): void
    {
        $contact = new Contact('test@mailjet.com');

        $this->assertNull($contact->getAction());
        $this->assertSame($contact, $contact->setAction(Contact::ACTION_REMOVE));
        $this->assertSame(Contact::ACTION_REMOVE, $contact->getAction());
    }

    public function testSetActionRejectsInvalidAction(): void
    {
        $contact = new Contact('test@mailjet.com');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('invalid: is not a valid Action.');

        $contact->setAction('invalid');
    }
}
