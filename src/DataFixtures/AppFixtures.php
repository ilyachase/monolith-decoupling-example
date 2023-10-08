<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $restaurant = (new Restaurant())->setName('My restaurant');
        $manager->persist($restaurant);

        $manager->flush();
    }
}
