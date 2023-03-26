<?php

declare(strict_types=1);

namespace App\Tests\Shop\Infrastructure\EventListener;

use App\Shop\Infrastructure\EventListener\HttpApiSerializeSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

class HttpApiSerializeSubscriberTest extends TestCase
{
    private HttpApiSerializeSubscriber $serializeSubscriber;
    private SerializerInterface $serializer;

    /**
     * When the system get the list of subscribed events
     * Then the subscriber return [KernelEvents::VIEW => 'onKernelController']
     */
    public function testGetSubscribedEvents(): void
    {
        $this::assertEquals(
            [KernelEvents::VIEW => 'onKernelController'],
            $this->serializeSubscriber::getSubscribedEvents()
        );
    }

    /**
     * Given a ViewEvent
     * When onKernelController is triggered with this event
     * Then serialize the event result and set the event response as JsonResponse
     */
    public function testOnKernelController(): void
    {
        $event = new ViewEvent(
            $this->createMock(HttpKernelInterface::class),
            $this->createMock(Request::class),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'bar']
        );
        $this->serializer->method('serialize')
            ->with($event->getControllerResult(), 'json');
        $this->serializeSubscriber->onKernelController($event);

        $this::assertInstanceOf(JsonResponse::class, $event->getResponse());
    }

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->serializeSubscriber = new HttpApiSerializeSubscriber($this->serializer);
    }
}
