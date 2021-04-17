<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Interfaces\Exceptions\EntityNotFoundExceptionInterface;
use Exception;

final class EntityNotFoundException extends Exception implements EntityNotFoundExceptionInterface
{
    private string $class;
    private array $criteria;

    public function __construct(string $class, array $criteria)
    {
        parent::__construct('Entity not found.');
        $this->class = $class;
        $this->criteria = $criteria;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getCriteria(): array
    {
        return $this->criteria;
    }
}
