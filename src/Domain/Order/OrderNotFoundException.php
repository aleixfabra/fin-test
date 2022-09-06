<?php

declare(strict_types=1);

namespace App\Domain\Order;

use App\Shared\Domain\NotFoundException;

final class OrderNotFoundException extends NotFoundException
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;

        parent::__construct($this->errorMessage());
    }

    protected function errorMessage(): string
    {
        return sprintf('The Order #%s does not exist', $this->id);
    }
}
