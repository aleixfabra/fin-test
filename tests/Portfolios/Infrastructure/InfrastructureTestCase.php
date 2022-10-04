<?php

declare(strict_types=1);

namespace App\Tests\Portfolios\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class InfrastructureTestCase extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
    }

    /**
     * @return mixed
     */
    protected function service(string $id)
    {
        return self::getContainer()->get($id);
    }

    protected function clearUnitOfWork(): void
    {
        $this->service(EntityManagerInterface::class)->clear();
    }
}
