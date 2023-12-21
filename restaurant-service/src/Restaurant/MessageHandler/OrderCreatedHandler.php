<?php

declare(strict_types=1);

namespace App\Restaurant\MessageHandler;

use App\Common\Message\OrderAccepted;
use App\Common\Message\OrderCreated;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
readonly class OrderCreatedHandler
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function __invoke(OrderCreated $message)
    {
        // for the sake of the example, let's assume for now that the order can always be served
        $this->messageBus->dispatch(new OrderAccepted());
        // alternatively, we could dispatch this instead based on our business logic:
        // $this->messageBus->dispatch(new OrderDeclined());
    }
}
