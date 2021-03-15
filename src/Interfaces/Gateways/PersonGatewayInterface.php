<?php

namespace App\Interfaces\Gateways;

use App\Entity\Person;
use App\Interfaces\Exceptions\EntityNotFoundExceptionInterface;

interface PersonGatewayInterface
{
    /**
     * @throws EntityNotFoundExceptionInterface
     */
    public function findByFirstAndLastName(string $firstName, string $lastName): Person;

    public function persist(Person $person): void;

    public function persistAndFlush(Person $person): void;
}
