<?php

namespace App\Controller;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface; //needed to be able to return a class
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; //needed to be used from platform API configuration (see User.php)

class GetClubsController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(User $data): User
    {
        $date = new DateTime();

        for ($i = \count($data->getAssoMembership()) - 1; $i >= 0; --$i) {
            if ($data->getAssoMembership()[$i]->getEndAt() < $date) {
                unset($data->getAssoMembership()[$i]);
            }
        }

        return $data;
    }
}
