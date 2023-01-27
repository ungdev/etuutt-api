<?php

namespace App\DataFixtures;

use App\Entity\Semester;
use App\Entity\UE;
use App\Entity\UECourse;
use App\Entity\User;
use App\Repository\SemesterRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UECourseSeeder extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            UserSeeder::class,
            UESeeder::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $users = $manager->getRepository(User::class)->findAll();
        $ues = $manager->getRepository(UE::class)->findAll();

        /** @var SemesterRepository $semesterRepository */
        $semesterRepository = $manager->getRepository(Semester::class);

        //  Création de 200 cours
        for ($i = 0; $i < 200; ++$i) {
            $course = new UECourse();
            $course->setUE($faker->randomElement($ues));
            $course->setDay($faker->randomElement(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']));
            $course->setStartHour(new \DateTime($faker->time()));
            $course->setEndHour(new \DateTime($faker->time()));
            if ($faker->boolean(30)) {
                $course->setWeek($faker->randomElement(['A', 'B']));
            }
            $course->setType($faker->randomElement(['CM', 'TD', 'TP']));
            $course->setCreatedAt($faker->dateTimeBetween('-3 years'));
            if ($faker->boolean()) {
                $course->setRoom('Internet');
            } else {
                $course->setRoom(strtoupper($faker->randomLetter.$faker->numberBetween(0, 2).$faker->numberBetween(0, 1).$faker->numberBetween(0, 9)));
            }
            $course->setSemester($semesterRepository->getSemesterOfDate($course->getCreatedAt()));
            $manager->persist($course);
        }
        $manager->flush();

        //  Inscription des étudiants aux cours
        $courses = $manager->getRepository(UECourse::class)->findAll();
        foreach ($courses as $course) {
            for ($i = 0; $i < $faker->numberBetween(10, 50); ++$i) {
                $course->addStudent($faker->randomElement($users));
            }
            $manager->persist($course);
        }
        $manager->flush();
    }
}
