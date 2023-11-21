<?php

declare(strict_types=1);

namespace App\Common\Client;

use RuntimeException;

class CustomerServiceClient extends AbstractHttpClient
{
    public function changeOrderStatus(int $orderId, string $newOrderStatus): void
    {
        $response = $this->sendServiceRequest(
            uri: '/service-customer/orders',
            requestBody: [
                'orderId' => $orderId,
                'newOrderStatus' => $newOrderStatus,
            ],
            method: 'POST'
        );

        if (200 !== $response->getStatusCode()) {
            throw new RuntimeException('Unexpected response code');
        }
    }

    protected function getServiceName(): string
    {
        return 'customer';
    }
}
