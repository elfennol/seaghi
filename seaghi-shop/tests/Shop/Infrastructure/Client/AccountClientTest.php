<?php

declare(strict_types=1);

namespace App\Tests\Shop\Infrastructure\Client;

use App\Shop\Infrastructure\Client\AccountClient;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class AccountClientTest extends TestCase
{
    public function testWithdrawReturnsTrue(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $response->method('toArray')->willReturn(['status' => true]);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with('GET', 'http://account-api/withdraw')
            ->willReturn($response);

        $client = new AccountClient($httpClient, 'http://account-api');
        $this::assertTrue($client->withDraw());
    }

    public function testWithdrawReturnsFalse(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $response->method('toArray')->willReturn(['status' => false]);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with('GET', 'http://account-api/withdraw')
            ->willReturn($response);

        $client = new AccountClient($httpClient, 'http://account-api');
        $this::assertFalse($client->withDraw());
    }
}
