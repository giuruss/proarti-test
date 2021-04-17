<?php

declare(strict_types=1);

namespace App\Interfaces\Exceptions;

interface EntityNotFoundExceptionInterface extends \Throwable
{
    public function getCriteria(): array;

    public function getClass(): string;
}
