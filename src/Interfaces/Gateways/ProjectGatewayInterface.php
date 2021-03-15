<?php

namespace App\Interfaces\Gateways;

use App\Entity\Project;
use App\Interfaces\Exceptions\EntityNotFoundExceptionInterface;

interface ProjectGatewayInterface
{
    /**
     * @throws EntityNotFoundExceptionInterface
     */
    public function findByName(string $name): Project;

    public function persist(Project $project): void;

    public function persistAndFlush(Project $project): void;
}
