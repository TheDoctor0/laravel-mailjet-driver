<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Exception;

use Mockery;
use Mailjet\Response;
use PHPUnit\Framework\TestCase;
use Mailjet\LaravelMailjet\Exception\MailjetException;

class MailjetExceptionTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function testItExtractsAllErrorFieldsFromResponse(): void
    {
        $response = $this->mockResponse(400, 'Bad Request', [
            'ErrorInfo' => 'info',
            'ErrorMessage' => 'message',
            'ErrorIdentifier' => 'identifier',
        ]);

        $exception = new MailjetException(0, 'MailjetService:get() failed', $response);

        $this->assertSame(400, $exception->getCode());
        $this->assertSame('MailjetService:get() failed: Bad Request', $exception->getMessage());
        $this->assertSame('info', $exception->getErrorInfo());
        $this->assertSame('message', $exception->getErrorMessage());
        $this->assertSame('identifier', $exception->getErrorIdentifier());
    }

    public function testGettersReturnNullOnPartialErrorBody(): void
    {
        $response = $this->mockResponse(401, 'Unauthorized', [
            'ErrorMessage' => 'API key authentication/authorization failure',
        ]);

        $exception = new MailjetException(0, 'MailjetService:get() failed', $response);

        $this->assertNull($exception->getErrorInfo());
        $this->assertSame('API key authentication/authorization failure', $exception->getErrorMessage());
        $this->assertNull($exception->getErrorIdentifier());
    }

    public function testItCanBeConstructedWithoutResponse(): void
    {
        $exception = new MailjetException(0, 'MailjetService:get() failed');

        $this->assertSame('MailjetService:get() failed', $exception->getMessage());
        $this->assertNull($exception->getErrorInfo());
        $this->assertNull($exception->getErrorMessage());
        $this->assertNull($exception->getErrorIdentifier());
    }

    /**
     * @param int    $status
     * @param string $reasonPhrase
     * @param array  $body
     *
     * @return Response|Mockery\MockInterface
     */
    protected function mockResponse(int $status, string $reasonPhrase, array $body): Response
    {
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('getStatus')->andReturn($status);
        $response->shouldReceive('getReasonPhrase')->andReturn($reasonPhrase);
        $response->shouldReceive('getBody')->andReturn($body);

        return $response;
    }
}
