<?php

namespace App\Controller;

use App\Entity\Semester;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        $now = new DateTime('now');
        $currentSemesterCode = $repository->getSemesterOfDate($now)->getCode();

        $nbCourses = \count($data->getCourses());

        for ($i = $nbCourses - 1; $i >= 0; --$i) {
            if ($data->getCourses()[$i]->getSemester()->getCode() !== $currentSemesterCode) {
                unset($data->getCourses()[$i]);
            }
        }

        return $data;
    }
}
