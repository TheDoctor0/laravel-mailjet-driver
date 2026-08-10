<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Services;

use Mockery;
use Mailjet\Resources;
use PHPUnit\Framework\TestCase;
use Mailjet\LaravelMailjet\Model\Contact;
use Mailjet\LaravelMailjet\Model\ContactsList;
use Mailjet\LaravelMailjet\Services\MailjetService;
use Mailjet\LaravelMailjet\Exception\MailjetException;
use Mailjet\LaravelMailjet\Tests\MocksMailjetResponses;
use Mailjet\LaravelMailjet\Services\ContactsListService;

class ContactsListServiceTest extends TestCase
{
    use MocksMailjetResponses;

    /**
     * @var MailjetService|Mockery\MockInterface
     */
    private $mailjet;

    /**
     * @var ContactsListService
     */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mailjet = Mockery::mock(MailjetService::class);
        $this->service = new ContactsListService($this->mailjet);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function testCreateSetsDefaultActionAndReturnsData(): void
    {
        $contact = new Contact('test@mailjet.com');
        $this->expectManageContact('1', $contact, Contact::ACTION_ADDFORCE, $this->successResponse([['ID' => 1]]));

        $this->assertSame([['ID' => 1]], $this->service->create('1', $contact));
        $this->assertSame(Contact::ACTION_ADDFORCE, $contact->getAction());
    }

    public function testCreateThrowsOnFailure(): void
    {
        $contact = new Contact('test@mailjet.com');
        $this->expectManageContact('1', $contact, Contact::ACTION_ADDFORCE, $this->failureResponse());

        $this->expectException(MailjetException::class);
        $this->expectExceptionMessage('ContactsListService:create() failed: Bad Request');

        $this->service->create('1', $contact);
    }

    public function testUpdateSetsDefaultActionAndReturnsData(): void
    {
        $contact = new Contact('test@mailjet.com');
        $this->expectManageContact('1', $contact, Contact::ACTION_ADDNOFORCE, $this->successResponse([['ID' => 1]]));

        $this->assertSame([['ID' => 1]], $this->service->update('1', $contact));
        $this->assertSame(Contact::ACTION_ADDNOFORCE, $contact->getAction());
    }

    public function testUpdateThrowsOnFailure(): void
    {
        $contact = new Contact('test@mailjet.com');
        $this->expectManageContact('1', $contact, Contact::ACTION_ADDNOFORCE, $this->failureResponse());

        $this->expectException(MailjetException::class);
        $this->expectExceptionMessage('ContactsListService:update() failed: Bad Request');

        $this->service->update('1', $contact);
    }

    public function testSubscribeForcesResubscriptionByDefault(): void
    {
        $contact = new Contact('test@mailjet.com');
        $this->expectManageContact('1', $contact, Contact::ACTION_ADDFORCE, $this->successResponse([['ID' => 1]]));

        $this->assertSame([['ID' => 1]], $this->service->subscribe('1', $contact));
        $this->assertSame(Contact::ACTION_ADDFORCE, $contact->getAction());
    }

    public function testSubscribeWithoutForceUsesAddNoForce(): void
    {
        $contact = new Contact('test@mailjet.com');
        $this->expectManageContact('1', $contact, Contact::ACTION_ADDNOFORCE, $this->successResponse([['ID' => 1]]));

        $this->assertSame([['ID' => 1]], $this->service->subscribe('1', $contact, false));
        $this->assertSame(Contact::ACTION_ADDNOFORCE, $contact->getAction());
    }

    public function testSubscribeThrowsOnFailure(): void
    {
        $contact = new Contact('test@mailjet.com');
        $this->expectManageContact('1', $contact, Contact::ACTION_ADDFORCE, $this->failureResponse());

        $this->expectException(MailjetException::class);
        $this->expectExceptionMessage('ContactsListService:subscribe() failed: Bad Request');

        $this->service->subscribe('1', $contact);
    }

    public function testUnsubscribeUsesUnsubAction(): void
    {
        $contact = new Contact('test@mailjet.com');
        $this->expectManageContact('1', $contact, Contact::ACTION_UNSUB, $this->successResponse([['ID' => 1]]));

        $this->assertSame([['ID' => 1]], $this->service->unsubscribe('1', $contact));
        $this->assertSame(Contact::ACTION_UNSUB, $contact->getAction());
    }

    public function testUnsubscribeThrowsOnFailure(): void
    {
        $contact = new Contact('test@mailjet.com');
        $this->expectManageContact('1', $contact, Contact::ACTION_UNSUB, $this->failureResponse());

        $this->expectException(MailjetException::class);
        $this->expectExceptionMessage('ContactsListService:unsubscribe() failed: Bad Request');

        $this->service->unsubscribe('1', $contact);
    }

    public function testDeleteUsesRemoveAction(): void
    {
        $contact = new Contact('test@mailjet.com');
        $this->expectManageContact('1', $contact, Contact::ACTION_REMOVE, $this->successResponse([['ID' => 1]]));

        $this->assertSame([['ID' => 1]], $this->service->delete('1', $contact));
        $this->assertSame(Contact::ACTION_REMOVE, $contact->getAction());
    }

    public function testDeleteThrowsOnFailure(): void
    {
        $contact = new Contact('test@mailjet.com');
        $this->expectManageContact('1', $contact, Contact::ACTION_REMOVE, $this->failureResponse());

        $this->expectException(MailjetException::class);
        $this->expectExceptionMessage('ContactsListService:delete() failed: Bad Request');

        $this->service->delete('1', $contact);
    }

    public function testUpdateEmailCopiesOldPropertiesAndRemovesOldContact(): void
    {
        $contact = new Contact('new@mailjet.com');

        $this->mailjet->shouldReceive('get')
            ->once()
            ->with(Resources::$Contactdata, ['id' => 'old@mailjet.com'])
            ->andReturn($this->successResponse([['Data' => ['FirstName' => 'John']]]));

        $this->mailjet->shouldReceive('post')
            ->once()
            ->with(Resources::$ContactslistManagecontact, ['id' => '1', 'body' => [
                Contact::EMAIL_KEY => 'new@mailjet.com',
                Contact::PROPERTIES_KEY => ['FirstName' => 'John'],
                Contact::ACTION_KEY => Contact::ACTION_ADDFORCE,
            ]])
            ->andReturn($this->successResponse([['ID' => 1]]));

        $this->mailjet->shouldReceive('post')
            ->once()
            ->with(Resources::$ContactslistManagecontact, ['id' => '1', 'body' => [
                Contact::EMAIL_KEY => 'old@mailjet.com',
                Contact::PROPERTIES_KEY => [],
                Contact::ACTION_KEY => Contact::ACTION_REMOVE,
            ]])
            ->andReturn($this->successResponse([['ID' => 2]]));

        $this->assertSame([['ID' => 2]], $this->service->updateEmail('1', $contact, 'old@mailjet.com'));
    }

    public function testUpdateEmailWithoutOldContactDataSkipsPropertyCopy(): void
    {
        $contact = new Contact('new@mailjet.com');

        $this->mailjet->shouldReceive('get')
            ->once()
            ->with(Resources::$Contactdata, ['id' => 'old@mailjet.com'])
            ->andReturn($this->successResponse([]));

        $this->mailjet->shouldReceive('post')
            ->twice()
            ->andReturn($this->successResponse([['ID' => 2]]));

        $this->assertSame([['ID' => 2]], $this->service->updateEmail('1', $contact, 'old@mailjet.com'));
    }

    public function testUpdateEmailThrowsWhenOldContactLookupFails(): void
    {
        $contact = new Contact('new@mailjet.com');

        $this->mailjet->shouldReceive('get')->once()->andReturn($this->failureResponse());
        $this->mailjet->shouldReceive('post')->never();

        $this->expectException(MailjetException::class);
        $this->expectExceptionMessage('ContactsListService:changeEmail() failed: Bad Request');

        $this->service->updateEmail('1', $contact, 'old@mailjet.com');
    }

    public function testUpdateEmailThrowsWhenAddingNewContactFails(): void
    {
        $contact = new Contact('new@mailjet.com');

        $this->mailjet->shouldReceive('get')->once()->andReturn($this->successResponse([]));
        $this->mailjet->shouldReceive('post')->once()->andReturn($this->failureResponse());

        $this->expectException(MailjetException::class);
        $this->expectExceptionMessage('ContactsListService:changeEmail() failed: Bad Request');

        $this->service->updateEmail('1', $contact, 'old@mailjet.com');
    }

    public function testUpdateEmailThrowsWhenRemovingOldContactFails(): void
    {
        $contact = new Contact('new@mailjet.com');

        $this->mailjet->shouldReceive('get')->once()->andReturn($this->successResponse([]));
        $this->mailjet->shouldReceive('post')->once()->andReturn($this->successResponse([['ID' => 1]]))->ordered();
        $this->mailjet->shouldReceive('post')->once()->andReturn($this->failureResponse())->ordered();

        $this->expectException(MailjetException::class);
        $this->expectExceptionMessage('ContactsListService:changeEmail() failed: Bad Request');

        $this->service->updateEmail('1', $contact, 'old@mailjet.com');
    }

    public function testUploadManyContactsListSendsContactsInBatches(): void
    {
        $contacts = [];
        for ($i = 0; $i < ContactsListService::CONTACT_BATCH_SIZE + 1; $i++) {
            $contacts[] = new Contact("contact{$i}@mailjet.com");
        }

        $list = new ContactsList('1', ContactsList::ACTION_ADDFORCE, $contacts);

        $this->mailjet->shouldReceive('post')
            ->twice()
            ->with(Resources::$ContactslistManagemanycontacts, Mockery::on(static function (array $args): bool {
                return $args['id'] === '1'
                    && $args['body']['Action'] === ContactsList::ACTION_ADDFORCE
                    && count($args['body']['Contacts']) > 0;
            }))
            ->andReturn(
                $this->successResponse([['JobID' => 1]]),
                $this->successResponse([['JobID' => 2]])
            );

        $this->assertSame(
            [['JobID' => 1], ['JobID' => 2]],
            $this->service->uploadManyContactsList($list)
        );
    }

    public function testUploadManyContactsListThrowsOnFailure(): void
    {
        $list = new ContactsList('1', ContactsList::ACTION_ADDFORCE, [new Contact('test@mailjet.com')]);

        $this->mailjet->shouldReceive('post')->once()->andReturn($this->failureResponse());

        $this->expectException(MailjetException::class);
        $this->expectExceptionMessage('ContactsListService:manageManyContactsList() failed: Bad Request');

        $this->service->uploadManyContactsList($list);
    }

    private function expectManageContact(string $id, Contact $contact, string $action, $response): void
    {
        $this->mailjet->shouldReceive('post')
            ->once()
            ->with(Resources::$ContactslistManagecontact, Mockery::on(
                static function (array $args) use ($id, $contact, $action): bool {
                    return $args['id'] === $id
                        && $args['body'][Contact::EMAIL_KEY] === $contact->getEmail()
                        && $args['body'][Contact::ACTION_KEY] === $action;
                }
            ))
            ->andReturn($response);
    }
}
