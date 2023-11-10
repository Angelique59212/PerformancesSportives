<?php

namespace App\Controller;

use App\Entity\TypeActivity;
use App\Repository\TypeActivityRepository;
use App\Service\ErrorValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/typeActivity')]
class TypeActivityController extends AbstractController
{
    private SerializerInterface $serializer;
    private TypeActivityRepository $typeActivityRepository;
    private EntityManagerInterface $entityManager;

    /**
     * @param SerializerInterface $serializer
     * @param TypeActivityRepository $typeActivityRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(SerializerInterface $serializer, TypeActivityRepository $typeActivityRepository, EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->typeActivityRepository = $typeActivityRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/test', name: 'app_typeActivity_test', methods: ['GET'])]
    public function getTest(): JsonResponse
    {
        return new JsonResponse(["data"=>"test"], Response::HTTP_OK);
    }

    #[Route('/', name: 'app_typeActivity', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize(
                $this->typeActivityRepository->findAll(), 'json', ['groups' => 'getTypeActivity']), Response::HTTP_OK, [], true);
    }

    #[Route('/new', name: 'app_typeActivity_new', methods: ['POST'])]
    public function new(Request $request, ErrorValidatorService $errorValidatorService): JsonResponse
    {
        $typeActivity = $this->serializer->deserialize($request->getContent(), TypeActivity::class, 'json');

        $errors = $errorValidatorService->getErrors($typeActivity);
        if (count($errors) > 0) {
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($typeActivity);
        $this->entityManager->flush();

        return new JsonResponse
        ($this->serializer->serialize($typeActivity, 'json', ['groups' => 'getTypeActivity']), Response::HTTP_CREATED, [], true);
    }

    #[Route('/show/{id}', name: 'app_typeActivity_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $typeActivity = $this->typeActivityRepository->find($id);
        if ($typeActivity) {
            return new JsonResponse($this->serializer->serialize($typeActivity, 'json', ['groups' => 'getTypeActivity']), Response::HTTP_OK, [], true);
        }
        return new JsonResponse(["message" => "Le type d'activité n'est pas trouvé"], Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'app_typeActivity_edit', methods: ['PUT'])]
    public function edit(Request $request, TypeActivity $currentTypeActivity, ErrorValidatorService $errorValidatorService): JsonResponse
    {
        $editTypeActivity = $this->serializer->deserialize(
            $request->getContent(),
            TypeActivity::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $currentTypeActivity]);

        $errors = $errorValidatorService->getErrors($editTypeActivity);
        if (count($errors) > 0) {
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($editTypeActivity);
        $this->entityManager->flush();

        return new JsonResponse(['message' => "Type d'activité mise à jour", Response::HTTP_OK]);
    }

    #[Route('/{id}', name: 'app_typeActivity_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $typeActivity = $this->typeActivityRepository->find($id);
        if ($typeActivity) {
            $this->entityManager->remove($typeActivity);
            $this->entityManager->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse(['message' => "Type d'activité non trouvé"], Response::HTTP_NOT_FOUND);
    }
}
