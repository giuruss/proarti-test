<?php

namespace App\Interfaces\Exceptions;

interface EntityNotFoundExceptionInterface extends \Throwable
{
    public function getCriteria(): array;

    public function getClass(): string;
}
