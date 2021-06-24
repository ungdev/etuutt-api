<?php

namespace App\Controller;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SoftDeleteController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke($data)
    {
        dump($data);
        
        $deletedAt = $data->getDeletedAt();
        if (is_null($deletedAt)) {
            $data->setDeletedAt(new DateTime());
            $this->manager->persist($data);
            $this->manager->flush();
            $response = new Response('This element has been soft deleted successfully.', 200);
        } else {
            $response = new Response('This element has already been deleted.', 400);
        }
        dump($data);
        return $response;
    }
}
