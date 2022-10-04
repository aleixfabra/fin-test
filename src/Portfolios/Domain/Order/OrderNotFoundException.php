<?php

declare(strict_types=1);

namespace App\Portfolios\Domain\Order;

use App\Shared\Domain\NotFoundException;

final class OrderNotFoundException extends NotFoundException
{
    protected function errorMessage(): string
    {
        return sprintf('The Order #%s does not exist', $this->id);
    }
}
