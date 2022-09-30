<?php

declare(strict_types=1);

namespace App\Portfolios\Domain\Portfolio;

use App\Portfolios\Domain\Allocation\Allocation;
use App\Portfolios\Domain\Order\Order;
use App\Shared\Domain\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Portfolio implements Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var Allocation[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Portfolios\Domain\Allocation\Allocation",
     *     mappedBy="portfolio",
     *     cascade={"persist"}
     * )
     */
    private $allocations;

    /**
     * @var Order[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Portfolios\Domain\Order\Order",
     *     mappedBy="portfolio",
     *     cascade={"persist"}
     * )
     */
    private $orders;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->allocations = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param Allocation[] $allocations
     */
    public function addAllocations(array $allocations): void
    {
        foreach ($allocations as $allocation) {
            $this->addAllocation($allocation);
        }
    }

    public function addAllocation(Allocation $allocation): void
    {
        $this->allocations->add($allocation);
    }

    /**
     * @return Allocation[]|ArrayCollection
     */
    public function getAllocations()
    {
        return $this->allocations;
    }

    public function getAllocationsToPrimitives(): array
    {
        return array_map(function (Allocation $allocation) {
            return $allocation->toPrimitives();
        }, $this->getAllocations()->getValues());
    }

    /**
     * @return Order[]|ArrayCollection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->id,
            'allocations' => $this->getAllocationsToPrimitives(),
        ];
    }
}
