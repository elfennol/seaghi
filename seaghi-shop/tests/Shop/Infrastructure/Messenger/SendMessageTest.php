<?php

declare(strict_types=1);

namespace App\Tests\Shop\Infrastructure\Messenger;

use App\Shop\Infrastructure\Messenger\SendMessage;
use App\Shop\Port\Out\MessageContract\MonsterSoldMessage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class SendMessageTest extends TestCase
{
    public function testSendDispatchesMessage(): void
    {
        $message = new MonsterSoldMessage('Gargamel', 'Cat', 'lol_cat', 3);
        $mockBus = $this->createMock(MessageBusInterface::class);
        $mockBus->expects($this->once())
            ->method('dispatch')
            ->with($message)
            ->willReturn(new Envelope($message));

        $sendMessage = new SendMessage($mockBus);
        $sendMessage->send($message);
    }
}
