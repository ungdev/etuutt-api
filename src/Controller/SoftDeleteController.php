<?php

namespace App\Controller;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SoftDeleteController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke($data)
    {
        if($data instanceof User) {
            $data = $data->getTimestamps();
        }
        $deletedAt = $data->getDeletedAt();
        if (null === $deletedAt) {
            $data->setDeletedAt(new DateTime());
            $this->manager->persist($data);
            $this->manager->flush();
            $body = [
                'message' => 'This element has been soft deleted successfully.'
            ];
            $response = new JsonResponse($body, Response::HTTP_NO_CONTENT);
        } else {
            $body = [
                'error' => 'This element has already been deleted.'
            ];
            $response = new JsonResponse($body, Response::HTTP_NOT_FOUND);
        }

        return $response;
    }
}