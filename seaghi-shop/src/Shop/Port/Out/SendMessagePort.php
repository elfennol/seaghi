<?php

declare(strict_types=1);

namespace App\Shop\Port\Out;

use App\Shop\Port\Out\MessageContract\MessageMc;

/**
 * Send a message.
 */
interface SendMessagePort
{
    public function send(MessageMc $message): void;
}
