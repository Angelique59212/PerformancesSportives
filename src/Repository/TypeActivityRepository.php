<?php

namespace App\Repository;

use App\Entity\TypeActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeActivity>
 *
 * @method TypeActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeActivity[]    findAll()
 * @method TypeActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeActivity::class);
    }
}
