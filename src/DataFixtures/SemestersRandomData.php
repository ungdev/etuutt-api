<?php

namespace App\DataFixtures;

use App\Entity\Semester;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\Date;

class SemestersRandomData extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $iteratorDate = new DateTime('1994-01-31');
        $nowDate = new DateTime("now");

        while ($iteratorDate < $nowDate) {
            $startDate = clone $iteratorDate;
            $iteratorDate->modify('+6 month');
            $endDate = clone $iteratorDate;

            
            if ($startDate->format("Y") != $endDate->format("Y")) {
                $code = "A";
            } else {
                $code = "P";
            }
            $code .= substr($startDate->format("Y"), -2);
            
            $semester = new Semester($code);
            $semester->setStart($startDate);
            $semester->setEnd($endDate);

            $manager->persist($semester);
        }

        $manager->flush();

    }


}
