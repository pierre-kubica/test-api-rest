<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/users')]
class UserController extends AbstractController
{
    #[Route(name: 'app_user_get_all', methods: ['GET'])]
    public function getAllUsers(UserRepository $repository): JsonResponse
    {
        $users = $repository->findAll();

        if(!count($users))
        {
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse($this->serializer->serialize($users, 'json'), Response::HTTP_OK, [], true);
    }

    #[Route(name: 'app_user_create', methods: ['POST'])]
    public function createUser(): JsonResponse
    {
        $user = new User();

        if(isset($json['firstname']))
        {
            $user->setFirstname($json['firstname']);
        }

        if(isset($json['lastname']))
        {
            $user->setFirstname($json['lastname']);
        }

        $this->em->persist($user);

        try
        {
            $this->em->flush();
            $jsonResponse = new JsonResponse(['message' => 'success', 'guid' => $user->getGuid()], Response::HTTP_CREATED);
        }
        catch(\Exception $exception)
        {
            $jsonResponse = new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $jsonResponse;
    }

    #[Route('/{guid}', name: 'app_user_get_by_id', methods: ['GET'])]
    public function getUserById(string $guid, UserRepository $repository): JsonResponse
    {
        $user = $repository->findOneBy(['guid' => $guid]);

        if(!$user)
        {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($this->serializer->serialize($user, 'json'), Response::HTTP_OK, [], true);
    }

    #[Route('/{guid}', name: 'app_user_update', methods: ['PUT'])]
    public function updateUser(string $guid, UserRepository $repository): JsonResponse
    {
        $user = $repository->findOneBy(['guid' => $guid]);

        if(!$user)
        {
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        $json = json_decode($this->request->getContent());

        if(isset($json['firstname']))
        {
            $user->setFirstname($json['firstname']);
        }

        if(isset($json['lastname']))
        {
            $user->setFirstname($json['lastname']);
        }

        try
        {
            $this->em->flush();
            $jsonResponse = new JsonResponse(['message' => 'success'], Response::HTTP_OK);
        }
        catch(\Exception $exception)
        {
            $jsonResponse = new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $jsonResponse;
    }

    #[Route('/{guid}', name: 'app_user_delete', methods: ['DELETE'])]
    public function deleteUser(string $guid, UserRepository $repository): JsonResponse
    {
        $user = $repository->findOneBy(['guid' => $guid]);

        if(!$user)
        {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        try
        {
            $this->em->remove($user);
            $this->em->flush();
            $jsonResponse = new JsonResponse(['message' => 'success'], Response::HTTP_OK);
        }
        catch(\Exception $exception)
        {
            $jsonResponse = new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $jsonResponse;
    }
}
