<?php

namespace App\Portfolios\Infrastructure\DataFixtures;

use App\Portfolios\Domain\Allocation\Allocation;
use App\Portfolios\Domain\Portfolio\Portfolio;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $portfolio = new Portfolio(1);
        $portfolio->addAllocation(
            new Allocation(
                1,
                3,
                $portfolio,
            )
        );
        $portfolio->addAllocation(
            new Allocation(
                2,
                4,
                $portfolio,
            )
        );

        $manager->persist($portfolio);
        $manager->flush();
    }
}
