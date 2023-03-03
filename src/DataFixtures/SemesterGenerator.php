<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Semester;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SemesterGenerator extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $iteratorDate = new DateTime('1994-01-31');
        $limitDate = new DateTime('2090-01-31');

        while ($iteratorDate < $limitDate) {
            $startDate = clone $iteratorDate;
            $iteratorDate->modify('+6 month');
            $endDate = clone $iteratorDate;

            $code = $startDate->format('Y') !== $endDate->format('Y') ? 'A' : 'P';
            $code .= substr($startDate->format('Y'), -2);

            $semester = new Semester($code);
            $semester->setStart($startDate);
            $semester->setEnd($endDate);

            $manager->persist($semester);
        }

        $manager->flush();
    }
}
