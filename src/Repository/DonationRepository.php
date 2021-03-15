<?php

namespace App\Repository;

use App\Entity\Donation;
use App\Interfaces\Gateways\DonationGatewayInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

final class DonationRepository extends ServiceEntityRepository implements DonationGatewayInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Donation::class);
    }

    public function persist(Donation $donation): void
    {
        try {
            $this->_em->persist($donation);
        } catch (ORMException $e) {
        }
    }

    public function persistAndFlush(Donation $donation): void
    {
        try {
            $this->_em->persist($donation);
        } catch (ORMException $e) {
        }
        try {
            $this->_em->flush();
        } catch (OptimisticLockException | ORMException $e) {
        }
    }
}
