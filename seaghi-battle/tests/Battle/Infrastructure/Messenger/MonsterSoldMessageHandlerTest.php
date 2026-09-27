<?php

declare(strict_types=1);

namespace App\Tests\Battle\Infrastructure\Messenger;

use App\Battle\Application\UseCase\SpawnMonster;
use App\Battle\Infrastructure\Messenger\MessageValidatorInterface;
use App\Battle\Infrastructure\Messenger\MonsterSoldMessage;
use App\Battle\Infrastructure\Messenger\MonsterSoldMessageHandler;
use App\Battle\Port\DataContract\SpawnMonsterDto;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

class MonsterSoldMessageHandlerTest extends TestCase
{
    private MonsterSoldMessageHandler $messageHandler;
    private MessageValidatorInterface $validator;
    private SpawnMonster&MockObject $spawnMonster;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->validator = $this->createStub(MessageValidatorInterface::class);
        $this->spawnMonster = $this->createMock(SpawnMonster::class);
        $this->logger = $this->createStub(LoggerInterface::class);

        $this->messageHandler = new MonsterSoldMessageHandler(
            $this->validator,
            $this->spawnMonster,
            $this->logger,
        );
    }

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
            ->willReturn(new SpawnMonsterDto(Uuid::fromString('11111111-1111-1111-1111-111111111111'), 40, 10));

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
}
