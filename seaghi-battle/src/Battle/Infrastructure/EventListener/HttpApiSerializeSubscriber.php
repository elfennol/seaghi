<?php

declare(strict_types=1);

namespace App\Battle\Infrastructure\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Serialize the controller response.
 */
readonly class HttpApiSerializeSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {
    }

    public function onKernelController(ViewEvent $event): void
    {
        $event->setResponse(new JsonResponse(
            $this->serializer->serialize($event->getControllerResult(), 'json'),
            json: true,
        ));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => 'onKernelController',
        ];
    }
}
