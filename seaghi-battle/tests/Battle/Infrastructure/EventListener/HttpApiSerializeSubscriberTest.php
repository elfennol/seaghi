<?php

declare(strict_types=1);

namespace App\Tests\Battle\Infrastructure\EventListener;

use App\Battle\Infrastructure\EventListener\HttpApiSerializeSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

class HttpApiSerializeSubscriberTest extends TestCase
{
    /**
     * When the system get the list of subscribed events
     * Then the subscriber return [KernelEvents::VIEW => 'onKernelController']
     */
    public function testGetSubscribedEvents(): void
    {
        $this::assertEquals(
            [KernelEvents::VIEW => 'onKernelController'],
            HttpApiSerializeSubscriber::getSubscribedEvents()
        );
    }

    /**
     * Given a ViewEvent
     * When onKernelController is triggered with this event
     * Then serialize the event result and set the event response as JsonResponse
     */
    public function testOnKernelController(): void
    {
        $serializer = $this->createMock(SerializerInterface::class);
        $serializeSubscriber = new HttpApiSerializeSubscriber($serializer);

        $event = new ViewEvent(
            $this->createStub(HttpKernelInterface::class),
            $this->createStub(Request::class),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'bar']
        );
        $serializer->expects($this->once())
            ->method('serialize')
            ->with($event->getControllerResult(), 'json');
        $serializeSubscriber->onKernelController($event);

        $this::assertInstanceOf(JsonResponse::class, $event->getResponse());
    }
}
