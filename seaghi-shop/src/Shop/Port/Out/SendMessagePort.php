<?php

declare(strict_types=1);

namespace App\Shop\Port\Out;

/**
 * Send a message.
 */
interface SendMessagePort
{
    public function send(object $message): void;
}
