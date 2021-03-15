<?php

namespace App\Repository;

use App\Entity\Project;
use App\Exceptions\EntityNotFoundException;
use App\Interfaces\Gateways\ProjectGatewayInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

final class ProjectRepository extends ServiceEntityRepository implements ProjectGatewayInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function findByName(string $name): Project
    {
        try {
            return $this->createQueryBuilder('project')
                ->where('LOWER(project.name) = LOWER(:name)')
                ->setParameters([
                    'name' => $name,
                ])
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
            throw new EntityNotFoundException(Project::class, ['name' => $name]);
        } catch (NonUniqueResultException $e) {
            echo $e->getMessage();
            throw $e;
        }
    }

    public function persist(Project $project): void
    {
        try {
            $this->_em->persist($project);
        } catch (ORMException $e) {
        }
    }

    public function persistAndFlush(Project $project): void
    {
        try {
            $this->_em->persist($project);
        } catch (ORMException $e) {
        }
        try {
            $this->_em->flush();
        } catch (OptimisticLockException | ORMException $e) {
        }
    }
}
