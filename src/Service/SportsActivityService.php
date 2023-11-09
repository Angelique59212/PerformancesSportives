<?php

namespace App\Service;

use App\Entity\SportsActivity;
use App\Entity\User;
use App\Repository\SportsActivityRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class SportsActivityService
{
    private EntityManagerInterface $entityManager;
    private SportsActivityRepository $sportsActivityRepository;

    /**
     * @param SportsActivityRepository $sportsActivityRepository
     */
    public function __construct(EntityManagerInterface $entityManager, SportsActivityRepository $sportsActivityRepository)
    {
        $this->entityManager = $entityManager;
        $this->sportsActivityRepository = $sportsActivityRepository;
    }

    public function addActivity(SportsActivity $sportsActivity,$user): void
    {
        $sportsActivity->setDateActivity(new DateTime());
        $sportsActivity->setUser($user);

        $this->entityManager->persist($sportsActivity);
        $this->entityManager->flush();
    }

    public function getActivityByUser(int $id)
    {
        return $this->sportsActivityRepository->recoverActivityByUser($id);
    }

}