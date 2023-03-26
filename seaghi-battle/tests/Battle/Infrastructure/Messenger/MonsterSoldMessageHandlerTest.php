<?php

declare(strict_types=1);

namespace App\Tests\Battle\Infrastructure\Messenger;

use App\Battle\Infrastructure\Messenger\MessageValidatorInterface;
use App\Battle\Infrastructure\Messenger\MonsterSoldMessage;
use App\Battle\Infrastructure\Messenger\MonsterSoldMessageHandler;
use App\Battle\Port\In\DataContract\SpawnMonsterDto;
use App\Battle\Port\In\SpawnMonsterPort;
use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class MonsterSoldMessageHandlerTest extends TestCase
{
    private MonsterSoldMessageHandler $messageHandler;
    private MessageValidatorInterface $validator;
    private SpawnMonsterPort $spawnMonster;
    private LoggerInterface $logger;

    /**
     * Given a message
     * When the message is valid
     * Then a monster is spawned
     */
    public function testHandlerWhenMsgIsOk(): void
    {
        $message = new MonsterSoldMessage('John', 'Doe', 'category_code', 1);
        $this->spawnMonster
            ->expects($this->once())
            ->method('spawn')
            ->willReturn(new SpawnMonsterDto(1, 40, 10));

        ($this->messageHandler)($message);
    }

    /**
     * Given a message
     * When the message is not valid
     * Then no monster is spawned
     */
    public function testHandlerWhenMsgIsNotOk(): void
    {
        $message = new MonsterSoldMessage('John', 'Doe', 'category_code', 1);
        $this->validator->method('assertValid')->willThrowException(new Exception());
        $this->spawnMonster
            ->expects($this->never())
            ->method('spawn');

        ($this->messageHandler)($message);
    }

    protected function setUp(): void
    {
        $this->validator = $this->createMock(MessageValidatorInterface::class);
        $this->spawnMonster = $this->createMock(SpawnMonsterPort::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->messageHandler = new MonsterSoldMessageHandler(
            $this->validator,
            $this->spawnMonster,
            $this->logger,
        );
    }
}
