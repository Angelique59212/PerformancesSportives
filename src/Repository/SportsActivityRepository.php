<?php

namespace App\Repository;

use App\Entity\SportsActivity;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SportsActivity>
 *
 * @method SportsActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method SportsActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method SportsActivity[]    findAll()
 * @method SportsActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SportsActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SportsActivity::class);
    }

    public function recoverActivityByUser(int $id)
    {
        $query = $this->createQueryBuilder('s');
        $query->andWhere('s.user = :id')
            ->setParameter('id', $id);
        return $query->getQuery()->getResult();
    }

}
