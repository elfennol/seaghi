<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\AccountWithDraw;
use PHPUnit\Framework\TestCase;

class AccountWithDrawTest extends TestCase
{
    public function testInvokeReturnsSuccessfulJsonResponse(): void
    {
        $controller = new AccountWithDraw();
        $response = $controller();

        $this::assertSame(200, $response->getStatusCode());
        $this::assertSame('{"status":true}', $response->getContent());
    }
}
