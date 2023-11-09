<?php

namespace App\Controller;

use App\Entity\SportsActivity;
use App\Repository\SportsActivityRepository;
use App\Service\ErrorValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class SportsActivityController extends AbstractController
{
    private SerializerInterface $serializer;
    private SportsActivityRepository $sportsActivityRepository;
    private EntityManagerInterface $entityManager;

    /**
     * @param SerializerInterface $serializer
     * @param SportsActivityRepository $sportsActivityRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(SerializerInterface $serializer, SportsActivityRepository $sportsActivityRepository, EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->sportsActivityRepository = $sportsActivityRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_sportsActivity', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN", "Seul l'admin peut accéder")]
    public function getAll(): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize(
                $this->sportsActivityRepository->findAll(), 'json',['groups'=>'getSportsActivity']), Response::HTTP_OK, [], true);
    }

    #[Route('/new', name: 'app_sportsActivity_new', methods: ['POST'])]
    public function new(Request $request, ErrorValidatorService $errorValidatorService): JsonResponse
    {
        $sportsActivity = $this->serializer->deserialize($request->getContent(), SportsActivity::class, 'json');

        $errors = $errorValidatorService->getErrors($sportsActivity);
        if (count($errors) > 0) {
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($sportsActivity);
        $this->entityManager->flush();

        return new JsonResponse
        ($this->serializer->serialize($sportsActivity, 'json', ['groups'=>'getSportsActivity']), Response::HTTP_CREATED, [], true);
    }

    #[Route('/show/{id}', name: 'app_sportsActivity_show', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN", message: "Route non autorisé")]
    public function show(int $id): JsonResponse
    {
        $sportsActivity = $this->sportsActivityRepository->find($id);
        if ($sportsActivity) {
            return new JsonResponse($this->serializer->serialize($sportsActivity, 'json', ['groups'=>'getSportsActivity']), Response::HTTP_OK, [], true);
        }
        return new JsonResponse(["message" => "L'activité n'a pas été trouvée"], Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'app_sportsActivity_edit', methods: ['PUT'])]
    public function edit(Request $request, SportsActivity $currentSportsActivity, ErrorValidatorService $errorValidatorService): JsonResponse
    {
        $editSportsActivity = $this->serializer->deserialize(
            $request->getContent(),
            SportsActivity::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE=>$currentSportsActivity]);

        $errors = $errorValidatorService->getErrors($editSportsActivity);
        if (count($errors) > 0) {
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($editSportsActivity);
        $this->entityManager->flush();

        return new JsonResponse(['message'=>"Activité mise à jour", Response::HTTP_OK]);
    }

    #[Route('/{id}', name: 'app_sportsActivity_delete',methods: ['DELETE'])]
    #[IsGranted("ROLE_ADMIN", message: "Vous n'avez pas les droits")]
    public function delete(int $id): JsonResponse
    {
        $sportsActivity = $this->sportsActivityRepository->find($id);
        if ($sportsActivity) {
            $this->entityManager->remove($sportsActivity);
            $this->entityManager->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse(['message'=>"Activité non trouvée"], Response::HTTP_NOT_FOUND);
    }
}
