<?php

declare(strict_types=1);

namespace App\Battle\Infrastructure\Messenger;

use RuntimeException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class MessageValidator implements MessageValidatorInterface
{
    public function __construct(
        private ValidatorInterface $validator,
    ) {
    }

    public function assertValid(mixed $message): void
    {
        $errors = $this->validator->validate($message);

        if (count($errors) > 0) {
            $errorsString = (string)$errors;

            throw new RuntimeException($errorsString);
        }
    }
}
