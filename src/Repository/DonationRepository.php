<?php

namespace App\Repository;

use App\Entity\Donation;
use App\Entity\Person;
use App\Interfaces\Gateways\DonationGatewayInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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

    public function getDonationsTotalAmount(): int
    {
        $query = $this->createQueryBuilder('donations')
            ->select('SUM(donations.amount) as sum');

        return (int) $query->getQuery()->getSingleScalarResult();
    }

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getDonationsTotalAmountPerPerson(Person $person): int
    {
        $query = $this->createQueryBuilder('donations')
            ->select('SUM(donations.amount) as sum')
            ->andWhere('donations.person = :person')
            ->setParameter('person', $person);

        return (int) $query->getQuery()->getSingleScalarResult();
    }

}
