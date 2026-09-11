<?php

declare(strict_types=1);

namespace App\Tests\Battle\Infrastructure\Messenger;

use App\Battle\Infrastructure\Messenger\MonsterSoldMessage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MonsterSoldMessageValidationTest extends TestCase
{
    private ValidatorInterface $validator;

    public function testValidMonsterSoldMessageHasNoViolations(): void
    {
        $message = new MonsterSoldMessage('Gargoyle', 'Stone', 'category_code', 1);
        $violations = $this->validator->validate($message);

        $this::assertCount(0, $violations);
    }

    public function testInvalidMonsterSoldMessageViolations(): void
    {
        $message = new MonsterSoldMessage('', '', '', 0);
        $violations = $this->validator->validate($message);

        $this::assertGreaterThan(0, count($violations));
    }

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }
}
