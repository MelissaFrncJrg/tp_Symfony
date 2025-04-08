<?php

namespace App\DataFixtures;

use App\Factory\RecipeFactory;
use App\Factory\OriginFactory;
use App\Factory\TagFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TagFactory::createMany(10);
        OriginFactory::createMany(5);

        RecipeFactory::new()
            ->many(20)
            ->create(function() {
                return [
                    'tags' => TagFactory::randomRange(1, 3),
                    'origine' => OriginFactory::random(),
                ];
            });
    }
}
