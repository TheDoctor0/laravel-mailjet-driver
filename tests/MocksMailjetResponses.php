<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests;

use Mockery;
use Mailjet\Response;

trait MocksMailjetResponses
{
    /**
     * @return Response|Mockery\MockInterface
     */
    protected function successResponse(array $data = []): Response
    {
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('success')->andReturn(true);
        $response->shouldReceive('getData')->andReturn($data);

        return $response;
    }

    /**
     * @return Response|Mockery\MockInterface
     */
    protected function failureResponse(int $status = 400, string $reason = 'Bad Request', array $body = []): Response
    {
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('success')->andReturn(false);
        $response->shouldReceive('getStatus')->andReturn($status);
        $response->shouldReceive('getReasonPhrase')->andReturn($reason);
        $response->shouldReceive('getBody')->andReturn($body);

        return $response;
    }
}
