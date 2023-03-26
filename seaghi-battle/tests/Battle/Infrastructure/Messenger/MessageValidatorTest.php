<?php

declare(strict_types=1);

namespace App\Tests\Battle\Infrastructure\Messenger;

use App\Battle\Infrastructure\Messenger\MessageValidator;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MessageValidatorTest extends TestCase
{
    private MessageValidator $msgValidator;
    private ValidatorInterface $validator;

    /**
     * Given a message
     * When this message is valid
     * Then assertValid return nothing
     */
    public function testAssertValidWhenValid(): void
    {
        $this->validator
            ->method('validate')
            ->willReturn(new ConstraintViolationList([]));

        $this::assertNull($this->msgValidator->assertValid([]));
    }

    /**
     * Given a message
     * When this message is not valid
     * Then assertValid throw an exception
     */
    public function testAssertValidWhenNotValid(): void
    {
        $this->validator
            ->method('validate')
            ->willReturn(new ConstraintViolationList([
                new ConstraintViolation('msg', 'msgTemplate', [], 'value', 'propertyPath', 'invalidValue')
            ]));

        $this->expectException(Exception::class);
        $this->msgValidator->assertValid([]);
    }

    protected function setUp(): void
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->msgValidator = new MessageValidator($this->validator);
    }
}
