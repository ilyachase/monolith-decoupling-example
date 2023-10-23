<?php

namespace App\Restaurant\DataFixtures;

use App\Restaurant\Entity\Restaurant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RestaurantFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $restaurant = (new Restaurant())->setName('My restaurant');
        $manager->persist($restaurant);

        $manager->flush();
    }
}
