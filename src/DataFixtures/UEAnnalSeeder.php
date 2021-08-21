<?php

namespace App\DataFixtures;

use App\Entity\Semester;
use App\Entity\Translation;
use App\Entity\UE;
use App\Entity\UEAnnal;
use App\Entity\UEAnnalReport;
use App\Entity\UEAnnalReportReason;
use App\Entity\UEAnnalType;
use App\Entity\User;
use App\Util\Text;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UEAnnalSeeder extends Fixture implements DependentFixtureInterface
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
        $semesterRepo = $manager->getRepository(Semester::class);

        //  Création de 5 motifs de report
        for ($i = 0; $i < 5; ++$i) {
            $reportReason = new UEAnnalReportReason($faker->word.$faker->word);

            //  Création d'une traduction
            $descriptionTranslation = $reportReason->getDescriptionTranslation();

            $description = Text::createRandomText(5, 9);
            $descriptionTranslation->setFrench($description);
            $descriptionTranslation->setEnglish($description);
            $descriptionTranslation->setSpanish($description);
            $descriptionTranslation->setGerman($description);
            $descriptionTranslation->setChinese($description);

            $manager->persist($descriptionTranslation);
            $manager->persist($reportReason);
        }
        $manager->flush();

        //  Création de 5 types d'annals
        for ($i = 0; $i < 5; ++$i) {
            $type = new UEAnnalType($faker->word.$faker->word.$faker->word);
            $manager->persist($type);
        }
        $manager->flush();

        //  Création d'annals
        $types = $manager->getRepository(UEAnnalType::class)->findAll();
        for ($i = 0; $i < 200; ++$i) {
            $annal = new UEAnnal();
            $annal->setUE($faker->randomElement($ues));
            $annal->setSender($faker->randomElement($users));
            $annal->setCreatedAt($faker->dateTimeBetween('-3 years', 'now'));
            $annal->setSemester($semesterRepo->getSemesterOfDate($annal->getCreatedAt()));
            $annal->setType($faker->randomElement($types));
            $annal->setFilename($faker->imageUrl());
            $annal->setValidatedBy($faker->randomElement($users));
            if ($faker->boolean(1)) {
                $days = (new DateTime())->diff($annal->getCreatedAt())->days;
                $annal->setCreatedAt($faker->dateTimeBetween('-'.$days.' years', 'now'));
            }
            $manager->persist($annal);
        }
        $manager->flush();

        //  Création de 20 reports d'annal
        $annals = $manager->getRepository(UEAnnal::class)->findAll();
        $reportReasons = $manager->getRepository(UEAnnalReportReason::class)->findAll();
        for ($i = 0; $i < 20; ++$i) {
            $report = new UEAnnalReport();
            $report->setAnnal($faker->randomElement($annals));
            $report->setUser($faker->randomElement($users));
            $report->setReason($faker->randomElement($reportReasons));
            $body = Text::createRandomText(5, 9);
            $report->setBody($body);
            $report->setCreatedAt($faker->dateTimeBetween('-3 years', 'now'));
            $manager->persist($report);
        }
        $manager->flush();
    }
}
