<?php

declare(strict_types=1);

namespace App\Shop\Infrastructure\Messenger;

use App\Shop\Port\Out\MessageContract\MessageMc;
use App\Shop\Port\Out\SendMessagePort;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Generic class to send a message.
 */
class SendMessage implements SendMessagePort
{
    public function __construct(
        private MessageBusInterface $messageBus
    ) {
    }

    public function send(MessageMc $message): void
    {
        $this->messageBus->dispatch($message);
    }
}
