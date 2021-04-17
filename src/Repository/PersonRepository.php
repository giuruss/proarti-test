<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Person;
use App\Exceptions\EntityNotFoundException;
use App\Interfaces\Gateways\PersonGatewayInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

final class PersonRepository extends ServiceEntityRepository implements PersonGatewayInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function findByFirstAndLastName(string $firstName, string $lastName): Person
    {
        try {
            return $this->createQueryBuilder('person')
                ->where('LOWER(person.firstName) = LOWER(:firstName)')
                ->andWhere('LOWER(person.lastName) = LOWER(:lastName)')
                ->setParameters([
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                ])
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
            throw new EntityNotFoundException(Person::class, ['firstName' => $firstName, 'lastName' => $lastName]);
        } catch (NonUniqueResultException $e) {
            echo $e->getMessage();
            throw $e;
        }
    }

    public function persist(Person $person): void
    {
        try {
            $this->_em->persist($person);
        } catch (ORMException $e) {
        }
    }

    public function persistAndFlush(Person $person): void
    {
        try {
            $this->_em->persist($person);
        } catch (ORMException $e) {
        }
        try {
            $this->_em->flush();
        } catch (OptimisticLockException | ORMException $e) {
        }
    }

    public function findById(int $id): Person
    {
        return $this->createQueryBuilder('person')
            ->where('person.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult()
        ;
    }
}
