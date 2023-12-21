<?php

declare(strict_types=1);

namespace App\Restaurant\Controller;

use App\Common\Dto\Order;
use App\Restaurant\Service\RestaurantService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/restaurant')]
class ServiceApiController extends AbstractController
{
    #[Route('/service-restaurant/restaurants/{restaurantId}', methods: 'GET')]
    public function getRestaurant(int $restaurantId, RestaurantService $restaurantService): JsonResponse
    {
        $restaurant = $restaurantService->getRestaurant($restaurantId);

        if (!$restaurant) {
            return $this->json(['message' => 'Restaurant not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($restaurant);
    }

    #[Route('/service-restaurant/orders/actions/accept', methods: 'POST')]
    public function acceptOrder(Request $request, SerializerInterface $serializer, RestaurantService $restaurantService): JsonResponse
    {
        // todo: move to handler
        $newOrder = $serializer->deserialize($request->getContent(), Order::class, 'json');
        $result = $restaurantService->acceptOrder($newOrder);

        return $this->json($result);
    }
}
