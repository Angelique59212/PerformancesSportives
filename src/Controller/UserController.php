<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ErrorValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/user')]
class UserController extends AbstractController
{
    private SerializerInterface $serializer;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    /**
     * @param SerializerInterface $serializer
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(SerializerInterface $serializer, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_user', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN", "Seul l'admin peut accéder à tout les utilisateurs")]
    public function getAll(): JsonResponse
    {
       return new JsonResponse(
           $this->serializer->serialize(
               $this->userRepository->findAll(), 'json',['groups'=>'getUser']), Response::HTTP_OK, [], true);
    }

    #[Route('/new', name: 'app_user_new', methods: ['POST'])]
    public function new(Request $request, UserPasswordHasherInterface $passwordHasher, ErrorValidatorService $errorValidatorService): JsonResponse
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));

        $errors = $errorValidatorService->getErrors($user);
        if (count($errors) > 0) {
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse
        ($this->serializer->serialize($user, 'json', ['groups'=>'getUser']), Response::HTTP_CREATED, [], true);
    }

    #[Route('/show/{id}', name: 'app_user_show', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN", message: "Route non autorisé")]
    public function show(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if ($user) {
            return new JsonResponse($this->serializer->serialize($user, 'json', ['groups'=>'getUser']), Response::HTTP_OK, [], true);
        }
        return new JsonResponse(["message" => "L'utilisateur n'est pas trouvé"], Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'app_user_edit', methods: ['PUT'])]
    public function edit(Request $request, User $currentUser, ErrorValidatorService $errorValidatorService): JsonResponse
    {
        $editUser = $this->serializer->deserialize(
            $request->getContent(),
            User::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE=>$currentUser]);

        $errors = $errorValidatorService->getErrors($editUser);
        if (count($errors) > 0) {
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($editUser);
        $this->entityManager->flush();

        return new JsonResponse(['message'=>'Utilisateur mis à jour', Response::HTTP_OK]);
    }

    #[Route('/{id}', name: 'app_user_delete',methods: ['DELETE'])]
    #[IsGranted("ROLE_ADMIN", message: "Vous n'avez pas les droits")]
    public function delete(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if ($user) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse(['message'=>'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
    }
}
