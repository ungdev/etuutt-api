<?php

namespace App\Controller;

use App\Entity\Semester;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// This class is a controller that removes expired courses from the user's EDT
class GetEDTController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(User $data): User
    {
        $repository = $this->manager->getRepository(Semester::class);
        $currentSemesterCode = $repository->getSemesterOfDate(new DateTime())->getCode();

        $nbCourses = \count($data->getCourses());

        for ($i = $nbCourses - 1; $i >= 0; --$i) {
            if ($data->getCourses()[$i]->getSemester()->getCode() !== $currentSemesterCode) {
                //altering $data doesn't alter the database as it is only a representation
                unset($data->getCourses()[$i]);
            }
        }

        return $data;
    }
}
