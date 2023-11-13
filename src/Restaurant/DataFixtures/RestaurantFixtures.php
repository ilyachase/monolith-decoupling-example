<?php

namespace App\Restaurant\DataFixtures;

use App\Restaurant\Entity\Restaurant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class RestaurantFixtures extends Fixture
{
    public function __construct(private readonly EntityManagerInterface $restaurantEntityManager)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $restaurant = (new Restaurant())->setName('My restaurant');
        $this->restaurantEntityManager->persist($restaurant);

        $this->restaurantEntityManager->flush();
    }
}
