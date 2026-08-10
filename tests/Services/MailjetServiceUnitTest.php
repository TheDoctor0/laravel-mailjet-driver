<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Services;

use Mockery;
use Mailjet\Client;
use Mailjet\Resources;
use ReflectionProperty;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Mailjet\LaravelMailjet\Services\MailjetService;
use Mailjet\LaravelMailjet\Exception\MailjetException;
use Mailjet\LaravelMailjet\Tests\MocksMailjetResponses;

class MailjetServiceUnitTest extends TestCase
{
    use MocksMailjetResponses;

    /**
     * @var Client|Mockery\MockInterface
     */
    private $client;

    /**
     * @var MailjetService
     */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new MailjetService('key', 'secret', false);

        $this->client = Mockery::mock(Client::class);

        $property = new ReflectionProperty(MailjetService::class, 'client');
        $property->setValue($this->service, $this->client);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function testConstructorBuildsRealClient(): void
    {
        $service = new MailjetService('key', 'secret', false, ['version' => 'v3']);

        $this->assertInstanceOf(Client::class, $service->getClient());
    }

    public function testGetClientReturnsUnderlyingClient(): void
    {
        $this->assertSame($this->client, $this->service->getClient());
    }

    #[DataProvider('calls')]
    public function testMethodReturnsResponseOnSuccess(
        string $method,
        array $args,
        string $verb,
        array $clientCallArgs,
        string $error
    ): void {
        $response = $this->successResponse();

        $this->client->shouldReceive($verb)
            ->once()
            ->with(...$clientCallArgs)
            ->andReturn($response);

        $this->assertSame($response, $this->service->{$method}(...$args));
    }

    #[DataProvider('calls')]
    public function testMethodThrowsMailjetExceptionOnFailure(
        string $method,
        array $args,
        string $verb,
        array $clientCallArgs,
        string $error
    ): void {
        $this->client->shouldReceive($verb)
            ->once()
            ->with(...$clientCallArgs)
            ->andReturn($this->failureResponse(401, 'Unauthorized'));

        $this->expectException(MailjetException::class);
        $this->expectExceptionMessage("{$error}: Unauthorized");

        $this->service->{$method}(...$args);
    }

    public static function calls(): array
    {
        return [
            'post' => [
                'post', [Resources::$Email, ['body' => ['Messages' => []]], ['version' => 'v3.1']],
                'post', [Resources::$Email, ['body' => ['Messages' => []]], ['version' => 'v3.1']],
                'MailjetService:post() failed',
            ],
            'get' => [
                'get', [Resources::$Contact, ['id' => '1'], []],
                'get', [Resources::$Contact, ['id' => '1'], []],
                'MailjetService:get() failed',
            ],
            'put' => [
                'put', [Resources::$Contactdata, ['id' => '1', 'body' => ['Data' => []]], []],
                'put', [Resources::$Contactdata, ['id' => '1', 'body' => ['Data' => []]], []],
                'MailjetService:put() failed',
            ],
            'delete' => [
                'delete', [Resources::$Template, ['id' => '1'], []],
                'delete', [Resources::$Template, ['id' => '1'], []],
                'MailjetService:delete() failed',
            ],
            'getAllLists' => [
                'getAllLists', [['Limit' => 2]],
                'get', [Resources::$Contactslist, ['filters' => ['Limit' => 2]]],
                'MailjetService:getAllLists() failed',
            ],
            'createList' => [
                'createList', [['Name' => 'list']],
                'post', [Resources::$Contactslist, ['body' => ['Name' => 'list']]],
                'MailjetService:createList() failed',
            ],
            'getListRecipients' => [
                'getListRecipients', [['Limit' => 3]],
                'get', [Resources::$Listrecipient, ['filters' => ['Limit' => 3]]],
                'MailjetService:getListRecipients() failed',
            ],
            'getSingleContact' => [
                'getSingleContact', ['1'],
                'get', [Resources::$Contact, ['id' => '1']],
                'MailjetService:getSingleContact() failed',
            ],
            'createContact' => [
                'createContact', [['Email' => 'test@mailjet.com']],
                'post', [Resources::$Contact, ['body' => ['Email' => 'test@mailjet.com']]],
                'MailjetService:createContact() failed',
            ],
            'createListRecipient' => [
                'createListRecipient', [['ContactID' => '1', 'ListID' => '2']],
                'post', [Resources::$Listrecipient, ['body' => ['ContactID' => '1', 'ListID' => '2']]],
                'MailjetService:createListRecipient() failed',
            ],
            'editListRecipient' => [
                'editListRecipient', ['1', ['ContactID' => '1', 'ListID' => '2']],
                'put', [Resources::$Listrecipient, ['id' => '1', 'body' => ['ContactID' => '1', 'ListID' => '2']]],
                'MailjetService:editListrecipient() failed',
            ],
        ];
    }
}
