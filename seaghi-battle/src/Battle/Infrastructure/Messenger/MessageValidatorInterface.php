<?php

namespace App\Battle\Infrastructure\Messenger;

interface MessageValidatorInterface
{
    public function assertValid(mixed $message): void;
}
