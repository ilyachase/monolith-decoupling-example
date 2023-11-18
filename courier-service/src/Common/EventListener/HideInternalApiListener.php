<?php

declare(strict_types=1);

namespace App\Common\EventListener;

use App\Common\Client\AbstractSymfonyControllerResolvingClient;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsEventListener(event: 'kernel.request')]
class HideInternalApiListener
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $serviceApiUrlPattern = '/service-';
        $comparisonResult = strncmp($event->getRequest()->getPathInfo(), $serviceApiUrlPattern, mb_strlen($serviceApiUrlPattern));
        if (0 !== $comparisonResult) {
            return;
        }

        $secretKey = $event->getRequest()->attributes->get(AbstractSymfonyControllerResolvingClient::IS_INTERNAL_REQUEST_ATTRIBUTE_KEY);
        if (true !== $secretKey) {
            throw new NotFoundHttpException();
        }
    }
}
