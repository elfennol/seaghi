<?php

declare(strict_types=1);

namespace App\Shop\Infrastructure\Messenger;

use App\Shop\Port\Out\SendMessagePort;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Generic class to send a message.
 */
readonly class SendMessage implements SendMessagePort
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function send(object $message): void
    {
        $this->messageBus->dispatch($message);
    }
}
