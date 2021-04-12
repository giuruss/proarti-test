<?php

namespace App\Repository;

use App\Entity\Project;
use App\Entity\Reward;
use App\Exceptions\EntityNotFoundException;
use App\Interfaces\Gateways\RewardGatewayInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

final class RewardRepository extends ServiceEntityRepository implements RewardGatewayInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reward::class);
    }

    public function findByName(string $rewardName): Reward
    {
        try {
            return $this->createQueryBuilder('reward')
                ->where('LOWER(reward.name) = LOWER(:rewardName)')
                ->setParameters([
                    'rewardName' => $rewardName,
                ])
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
            throw new EntityNotFoundException(Reward::class, ['name' => $rewardName]);
        } catch (NonUniqueResultException $e) {
            echo $e->getMessage();
            throw $e;
        }
    }

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getTotalRewardsQuantityByProject(Project $project): int
    {
        $query = $this->createQueryBuilder('rewards')
            ->select('SUM(rewards.quantity) as sum')
            ->andWhere('rewards.project = :project')
            ->setParameter('project', $project);

        return (int) $query->getQuery()->getSingleScalarResult();
    }

    public function persist(Reward $reward): void
    {
        try {
            $this->_em->persist($reward);
        } catch (ORMException $e) {
        }
    }

    public function persistAndFlush(Reward $reward): void
    {
        try {
            $this->_em->persist($reward);
        } catch (ORMException $e) {
        }
        try {
            $this->_em->flush();
        } catch (OptimisticLockException | ORMException $e) {
        }
    }
}
