<?php

declare(strict_types=1);

namespace App\Tests\Battle\Infrastructure\Messenger;

use App\Battle\Infrastructure\Messenger\MessageSerializer;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class MessageSerializerTest extends TestCase
{
    private MessageSerializer $messageSerializer;
    private SerializerInterface $msgJsonSerializer;

    /**
     * Given an encoded envelop
     * When this encoded envelop is decode
     * Then the header type is prefixed with 'App\Battle\Infrastructure\Messenger\\'
     */
    public function testDecode(): void
    {
        $encodedEnvelope = [];
        $encodedEnvelope['headers']['type'] = 'MyType';
        $eEncodedEnvelop = [];
        $eEncodedEnvelop['headers']['type'] =
            'App\Battle\Infrastructure\Messenger\\' .
            $encodedEnvelope['headers']['type'];

        $envelop = new Envelope(new stdClass());
        $this->msgJsonSerializer
            ->expects($this->once())
            ->method('decode')
            ->with($eEncodedEnvelop)
            ->willReturn($envelop);

        $this->messageSerializer->decode($encodedEnvelope);
    }

    /**
     * Given an envelope
     * When this envelope is encoded
     * Then the header type is the class name without the namespace
     */
    public function testEncode(): void
    {
        $envelop = new Envelope(new stdClass());
        $data = [];
        $data['headers']['type'] = 'App\Battle\Infrastructure\Messenger\\MyType';
        $expectedData = [];
        $expectedData['headers']['type'] = 'MyType';
        $this->msgJsonSerializer
            ->expects($this->once())
            ->method('encode')
            ->with($envelop)
            ->willReturn($data);

        $this::assertEquals($expectedData, $this->messageSerializer->encode($envelop));
    }

    protected function setUp(): void
    {
        $this->msgJsonSerializer = $this->createMock(SerializerInterface::class);
        $this->messageSerializer = new MessageSerializer($this->msgJsonSerializer);
    }
}
