<?php

declare(strict_types=1);

namespace App\Battle\Port\Out;

/**
 * Transaction runner port.
 */
interface TransactionPort
{
    /**
     * @template T
     * @param callable(): T $operation
     * @return T
     */
    public function run(callable $operation): mixed;
}
