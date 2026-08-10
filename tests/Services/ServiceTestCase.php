<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Services;

use Mockery;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Mailjet\LaravelMailjet\Services\MailjetService;
use Mailjet\LaravelMailjet\Exception\MailjetException;
use Mailjet\LaravelMailjet\Tests\MocksMailjetResponses;

/**
 * Base test case for services delegating to MailjetService.
 *
 * Each row of calls() describes one service method:
 * [serviceMethod, serviceArgs, clientVerb, clientCallArgs, errorMessagePrefix]
 */
abstract class ServiceTestCase extends TestCase
{
    use MocksMailjetResponses;

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    /**
     * Create the service under test around the mocked MailjetService.
     */
    abstract protected function makeService(MailjetService $mailjet): object;

    /**
     * @return array<string, array{0: string, 1: array, 2: string, 3: array, 4: string}>
     */
    abstract public static function calls(): array;

    #[DataProvider('calls')]
    public function testMethodReturnsResponseDataOnSuccess(
        string $method,
        array $args,
        string $verb,
        array $clientCallArgs,
        string $error
    ): void {
        $data = [['ID' => 1, 'Name' => 'result']];

        $mailjet = Mockery::mock(MailjetService::class);
        $mailjet->shouldReceive($verb)
            ->once()
            ->with(...$clientCallArgs)
            ->andReturn($this->successResponse($data));

        $service = $this->makeService($mailjet);

        $this->assertSame($data, $service->{$method}(...$args));
    }

    #[DataProvider('calls')]
    public function testMethodThrowsMailjetExceptionOnFailure(
        string $method,
        array $args,
        string $verb,
        array $clientCallArgs,
        string $error
    ): void {
        $mailjet = Mockery::mock(MailjetService::class);
        $mailjet->shouldReceive($verb)
            ->once()
            ->with(...$clientCallArgs)
            ->andReturn($this->failureResponse(400, 'Bad Request'));

        $service = $this->makeService($mailjet);

        $this->expectException(MailjetException::class);
        $this->expectExceptionMessage("{$error}: Bad Request");

        $service->{$method}(...$args);
    }
}
