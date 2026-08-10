<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Model;

use RuntimeException;
use PHPUnit\Framework\TestCase;
use Mailjet\LaravelMailjet\Model\Contact;
use Mailjet\LaravelMailjet\Model\ContactsList;

class ContactsListTest extends TestCase
{
    public function testFormatContainsActionAndFormattedContacts(): void
    {
        $contact = new Contact('test@mailjet.com', ['FirstName' => 'John']);
        $list = new ContactsList('1', ContactsList::ACTION_ADDFORCE, [$contact]);

        $this->assertSame([
            'Action' => ContactsList::ACTION_ADDFORCE,
            'Contacts' => [$contact->format()],
        ], $list->format());
    }

    public function testGetters(): void
    {
        $contacts = [new Contact('test@mailjet.com')];
        $list = new ContactsList('1', ContactsList::ACTION_ADDNOFORCE, $contacts);

        $this->assertSame('1', $list->getListId());
        $this->assertSame(ContactsList::ACTION_ADDNOFORCE, $list->getAction());
        $this->assertSame($contacts, $list->getContacts());
    }

    public function testSetActionAcceptsValidAction(): void
    {
        $list = new ContactsList('1', ContactsList::ACTION_ADDFORCE, []);

        $this->assertSame($list, $list->setAction(ContactsList::ACTION_UNSUB));
        $this->assertSame(ContactsList::ACTION_UNSUB, $list->getAction());
    }

    public function testConstructorRejectsInvalidAction(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('invalid: is not a valid Action.');

        new ContactsList('1', 'invalid', []);
    }

    public function testSetActionRejectsInvalidAction(): void
    {
        $list = new ContactsList('1', ContactsList::ACTION_REMOVE, []);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('invalid: is not a valid Action.');

        $list->setAction('invalid');
    }
}
