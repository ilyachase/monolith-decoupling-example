<?php

declare(strict_types=1);

namespace App\Common\EventListener;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsEventListener(event: 'kernel.request')]
readonly class HideInternalApiListener
{
    public function __construct(
        #[Autowire('%api.secret.key%')]
        private string $apiSecretKey,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $serviceApiUrlPattern = '/api/customer/service-customer/';
        $comparisonResult = strncmp($event->getRequest()->getPathInfo(), $serviceApiUrlPattern, mb_strlen($serviceApiUrlPattern));
        if (0 !== $comparisonResult) {
            return;
        }

        $apiSecret = $event->getRequest()->headers->get('X-Api-Secret');
        if ($this->apiSecretKey !== $apiSecret) {
            throw new NotFoundHttpException();
        }
    }
}
