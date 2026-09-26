<?php

declare(strict_types=1);

namespace App\Battle\Infrastructure\Messenger;

use RuntimeException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

/**
 * The type header is set only to the class base name (without the namespace).
 * To communicate with other contexts.
 */
readonly class MessageSerializer implements SerializerInterface
{
    public function __construct(
        private SerializerInterface $msgJsonSerializer,
    ) {
    }

    public function decode(array $encodedEnvelope): Envelope
    {
        $encodedEnvelope['headers'] ??= [];
        $encodedEnvelope['headers']['type'] =
            'App\Battle\Infrastructure\Messenger\\' . $encodedEnvelope['headers']['type'];

        return $this->msgJsonSerializer->decode($encodedEnvelope);
    }

    public function encode(Envelope $envelope): array
    {
        $data = $this->msgJsonSerializer->encode($envelope);

        if (empty($data['headers']['type'])) {
            throw new RuntimeException(sprintf('Invalid data: %s', json_encode($data)));
        }

        $typeExplode = explode('\\', $data['headers']['type']);
        $data['headers']['type'] = end($typeExplode);

        return $data;
    }
}
