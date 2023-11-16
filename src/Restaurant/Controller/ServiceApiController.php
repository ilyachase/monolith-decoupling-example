<?php

declare(strict_types=1);

namespace App\Restaurant\Controller;

use App\Common\Dto\Order;
use App\Restaurant\Service\RestaurantService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ServiceApiController extends AbstractController
{
    #[Route('/service-restaurant/restaurants/{restaurantId}', methods: 'GET')]
    public function getRestaurant(int $restaurantId, RestaurantService $restaurantService): JsonResponse
    {
        $restaurant = $restaurantService->getRestaurant($restaurantId);

        if (!$restaurant) {
            return $this->json(['message' => 'Restaurant not found'], 404);
        }

        return $this->json($restaurant);
    }

    #[Route('/service-restaurant/order/actions/accept', methods: 'POST')]
    public function acceptOrder(Request $request, SerializerInterface $serializer, RestaurantService $restaurantService): JsonResponse
    {
        $newOrder = $serializer->deserialize($request->getContent(), Order::class, 'json');
        $result = $restaurantService->acceptOrder($newOrder);

        return $this->json($result);
    }
}
