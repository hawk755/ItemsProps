<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RestrictIpAddressSubscriber implements EventSubscriberInterface
{
    public function __construct(private string $X_API_KEY)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [RequestEvent::class => 'onKernelRequest'];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }
        $request = $event->getRequest();

        if ('api_entrypoint' == $request->attributes->get('_route')) {
            return;
        }

        if ($request->headers->get('X-API-KEY') !== $this->X_API_KEY) {
            throw new AccessDeniedHttpException('Access Denied');
        }
    }
}
