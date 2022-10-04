<?php

declare(strict_types=1);

namespace App\Shared\Domain;

abstract class NotFoundException extends FinizensException
{
    protected int $id;

    public function __construct(int $id)
    {
        $this->id = $id;

        parent::__construct($this->errorMessage());
    }

    abstract protected function errorMessage(): string;
}
