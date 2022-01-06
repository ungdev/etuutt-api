<?php

namespace App\Controller;

use App\Entity\UE;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/*
* This class is a controller that :
*   Removes names of users who leaved anonymous comments
*/
class GetUEController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(UE $data): UE
    {
        for ($i = \count($data->getComments()) - 1; $i >= 0; --$i) {
            if ($data->getComments()[$i]->getIsAnonymous()) {
                $data->getComments()[$i]->getAuthor()->setFirstName('Anonymous');
                $data->getComments()[$i]->getAuthor()->setLastName('');
            }
        }

        return $data;
    }
}
