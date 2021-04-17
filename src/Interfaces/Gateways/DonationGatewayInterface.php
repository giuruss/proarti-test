<?php

declare(strict_types=1);

namespace App\Interfaces\Gateways;

use App\Entity\Donation;

interface DonationGatewayInterface
{
    public function persist(Donation $donation): void;

    public function persistAndFlush(Donation $donation): void;
}
