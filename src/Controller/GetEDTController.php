<?php

namespace App\Controller;

use App\Entity\Semester;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface; //needed to be able to return a class
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; //needed to be used from platform API configuration (see User.php)

class GetEDTController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(User $data): User
    {
        $repository = $this->getDoctrine()->getRepository(Semester::class);
        $currentSemesterCode = $repository->getSemesterOfDate()->getCode();

        $nbCourses = \count($data->getCourses());

        for ($i = $nbCourses - 1; $i >= 0; --$i) {
            if ($data->getCourses()[$i]->getSemester()->getCode() !== $currentSemesterCode) {
                unset($data->getCourses()[$i]);
            }
        }

        return $data;
    }
}
