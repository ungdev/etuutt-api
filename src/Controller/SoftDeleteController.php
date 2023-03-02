<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * This class is a controller that soft delete the data passed to its "__invoke" method.
 */
class SoftDeleteController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $manager)
    {
    }

    public function __invoke($data)
    {
        //  Because the `deletedAt` property of a User is strore into its `timestamps` propety that refers to a UserTimestamps object.
        if ($data instanceof User) {
            $data = $data->getTimestamps();
        }

        $deletedAt = $data->getDeletedAt();
        if (null === $deletedAt) {
            $data->setDeletedAt(new \DateTime());
            $this->manager->persist($data);
            $this->manager->flush();
            $body = [
                'message' => 'This element has been soft deleted successfully.',
            ];
            $response = new JsonResponse($body, Response::HTTP_NO_CONTENT);
        } else {
            $body = [
                'error' => 'This element has already been deleted.',
            ];
            $response = new JsonResponse($body, Response::HTTP_NOT_FOUND);
        }

        return $response;
    }
}
