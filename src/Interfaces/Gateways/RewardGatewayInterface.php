<?php

declare(strict_types=1);

namespace App\Interfaces\Gateways;

use App\Entity\Reward;
use App\Interfaces\Exceptions\EntityNotFoundExceptionInterface;

interface RewardGatewayInterface
{
    /**
     * @throws EntityNotFoundExceptionInterface
     */
    public function findByName(string $rewardName): Reward;

    public function persist(Reward $reward): void;

    public function persistAndFlush(Reward $reward): void;

    public function findById(int $id): Reward;
}
