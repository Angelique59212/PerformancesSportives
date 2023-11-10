<?php

namespace App\Service;

use App\Entity\SportsActivity;
use App\Entity\TypeActivity;
use App\Repository\SportsActivityRepository;
use App\Repository\TypeActivityRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class SportsActivityService
{
    private EntityManagerInterface $entityManager;
    private SportsActivityRepository $sportsActivityRepository;
    private TypeActivityRepository $typeActivityRepository;

    /**
     * @param SportsActivityRepository $sportsActivityRepository
     */
    public function __construct(EntityManagerInterface $entityManager, SportsActivityRepository $sportsActivityRepository, TypeActivityRepository $typeActivityRepository)
    {
        $this->entityManager = $entityManager;
        $this->sportsActivityRepository = $sportsActivityRepository;
        $this->typeActivityRepository = $typeActivityRepository;
    }

    public function addActivity(SportsActivity $sportsActivity,$user,int $idTypeActivity): void
    {
        $sportsActivity->setDateActivity(new DateTime());
        $sportsActivity->setUser($user);

        $typeActivity = $this->typeActivityRepository->find($idTypeActivity);
        if ($typeActivity instanceof TypeActivity) {
            $sportsActivity->setTypeActivity($typeActivity);

            $this->entityManager->persist($sportsActivity);
            $this->entityManager->flush();
        } else {
            throw new Exception('ActivityType non trouvé', Response::HTTP_BAD_REQUEST);
        }
    }

    public function getActivityByUser(int $id)
    {
        return $this->sportsActivityRepository->recoverActivityByUser($id);
    }

    public function getActivityByUserDesc(int $id)
    {
        return $this->sportsActivityRepository->getActivityByDes($id);
    }

    public function getActivityByUserDuration(int $id)
    {
        return $this->sportsActivityRepository->getActivatyByDuration($id);
    }

    public function getActivityByUserCalorie(int $id)
    {
        return $this->sportsActivityRepository->getActivatyByCalorie($id);
    }

    public function getActivityTypeByUser(int $id)
    {
        return $this->sportsActivityRepository->getActivityTypeByUser($id);
    }
}