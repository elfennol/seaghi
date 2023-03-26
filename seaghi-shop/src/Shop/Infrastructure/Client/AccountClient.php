<?php

declare(strict_types=1);

namespace App\Shop\Infrastructure\Client;

use App\Shop\Port\Out\WithdrawFromAccountPort;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Get information from the external service Account.
 */
readonly class AccountClient implements WithdrawFromAccountPort
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $accountApiUrl,
    ) {
    }

    /**
     * Withdraw money.
     *
     * True if it's ok.
     * Return false if we can't.
     */
    public function withDraw(): bool
    {
        return $this->httpClient->request('GET', $this->accountApiUrl . '/withdraw')->toArray()['status'];
    }
}
