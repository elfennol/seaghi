<?php

declare(strict_types=1);

namespace App\Tests\Battle\Port\Out\Exception;

use App\Battle\Port\Out\Exception\MonsterNotFoundException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class MonsterNotFoundExceptionTest extends TestCase
{
    public function testForIdCreatesExceptionWithFormattedMessage(): void
    {
        $id = Uuid::v7();
        $exception = MonsterNotFoundException::forId($id);

        $this::assertInstanceOf(MonsterNotFoundException::class, $exception);
        $this::assertSame(sprintf('Monster with ID "%s" not found.', $id->toRfc4122()), $exception->getMessage());
    }
}
