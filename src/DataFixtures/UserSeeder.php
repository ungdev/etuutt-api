<?php

namespace App\DataFixtures;

use App\Entity\Semester;
use App\Entity\User;
use App\Entity\UserBan;
use App\Entity\UserBDEContribution;
use App\Entity\UserEtuUTTTeam;
use App\Repository\UserRepository;
use App\Util\Slug;
use App\Util\Text;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserSeeder extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            SemesterGenerator::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $semesterRepository = $manager->getRepository(Semester::class);

        //  Création d'un User administrateur
        $user = new User();
        $user->setFirstName('admin');
        $user->setLastName('admin');
        $user->setLogin('admin');
        $user->addRole('ROLE_ADMIN');
        $manager->persist($user);

        $userRepository = $manager->getRepository(User::class);
        for ($i = 0; $i < 300; ++$i) {
            //  Créations d'un User
            $user = new User();
            $user->setStudentId(44000 + $i);
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setLogin(self::generateLogin($user->getFirstName(), $user->getLastName(), $userRepository));
            $manager->persist($user);

            //  Création d'un timestamps pour chaque User
            $createdAt = $faker->dateTimeBetween('-3 years');
            $timestamps = $user->getTimestamps();
            $timestamps->setCreatedAt($createdAt);
            $days = (new DateTime())->diff($timestamps->getCreatedAt())->days;
            $timestamps->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days'));
            //  First and last login
            $timestamps->setFirstLoginDate($faker->dateTimeBetween('-30 days'));
            $days = (new DateTime())->diff($timestamps->getFirstLoginDate())->days;
            $timestamps->setLastLoginDate($faker->dateTimeBetween('-'.$days.' days'));
            //  Soft delete aléatoire d'un User (Avec une chance de 1%)
            if ($faker->boolean(1)) {
                $days = (new DateTime())->diff($timestamps->getLastLoginDate())->days;
                $timestamps->setDeletedAt($faker->dateTimeBetween('-'.$days.' days'));
            }

            //  Création d'un socialNetwork pour chaque User
            $socialNetwork = $user->getSocialNetwork();
            $sluggedName = Slug::slugify($user->getFirstName().'-'.$user->getLastName());
            if ($faker->boolean(75)) {
                $socialNetwork->setFacebook('https://facebook.com/'.$sluggedName);
            }
            if ($faker->boolean(75)) {
                $socialNetwork->setTwitter('https://twitter.com/'.$sluggedName);
            }
            if ($faker->boolean(75)) {
                $socialNetwork->setInstagram('https://instagram.com/'.$sluggedName);
            }
            if ($faker->boolean(75)) {
                $socialNetwork->setLinkedin('https://linkedin.com/'.$sluggedName);
            }
            if ($faker->boolean(75)) {
                $socialNetwork->setPseudoDiscord($faker->word);
            }
            $socialNetwork->setWantDiscordUTT($faker->boolean(75));

            //  Création d'un RGPD pour chaque User
            $RGPD = $user->getRGPD();
            if ($faker->boolean(75)) {
                $RGPD->setIsDeletingEverything($faker->boolean());
            }
            if ($faker->boolean(75)) {
                $RGPD->setIsKeepingAccount($faker->boolean());
            }

            //  Ban aléatoire d'un User (Avec une chance de 1%)
            if ($faker->boolean(1)) {
                //  Création d'un objet UserBan
                $userBan = new UserBan();
                $user->addBan($userBan);

                //  Random durée ban
                $days = (new DateTime())->diff($timestamps->getLastLoginDate())->days;

                //  50% de chance de ReadOnly, 50% de Banned
                if ($faker->boolean()) {
                    $userBan->setReadOnlyExpiration($faker->dateTimeBetween('-'.$days.' days', '+30 days'));
                } else {
                    $userBan->setBannedExpiration($faker->dateTimeBetween('-'.$days.' days', '+30 days'));
                }
            }

            //  Création d'une cotisation BDE pour quelques User
            if ($faker->boolean(75)) {
                $BDEContribution = new UserBDEContribution();
                $user->addBDEContribution($BDEContribution);

                $BDEContribution->setStart($faker->dateTimeBetween($createdAt));

                $contributionSemester = $semesterRepository->getSemesterOfDate($BDEContribution->getStart());

                $BDEContribution->setEnd($contributionSemester->getEnd());

                $BDEContribution->setStartSemester($contributionSemester);
                $BDEContribution->setEndSemester($contributionSemester);
            }

            //  On ajoute un User à la team EtuUTT
            if ($faker->boolean(2)) {
                $EtuUTTTeam = new UserEtuUTTTeam();
                $EtuUTTTeam->setUser($user);

                $role = Text::createRandomText(5, 9);
                $EtuUTTTeam->setRole($role);

                $EtuUTTMemberSemester = $semesterRepository->getSemesterOfDate($createdAt);
                $EtuUTTTeam->addSemester($EtuUTTMemberSemester);

                //  Une chance sur deux que l'User soit dans la team deux semestres de suite
                if ($faker->boolean()) {
                    $EtuUTTMemberSemester = $semesterRepository->getNextSemester($EtuUTTMemberSemester);
                    $EtuUTTTeam->addSemester($EtuUTTMemberSemester);
                }

                $manager->persist($EtuUTTTeam);
            }

            //  Sauvegarde des actions précédentes en DB
            $manager->flush();
        }
    }

    /**
     * Cette fonction génère le login à partir du firstname et du lastname.
     *
     * @param string         $firstName      Le prénom de l'utilisateur
     * @param string         $lastName       Le nom de famille de l'utilisateur
     * @param UserRepository $userRepository Le repository pour accéder aux Users
     *
     * @return string $login Le login de l'utilisateur
     */
    public static function generateLogin(string $firstName = null, string $lastName = null, UserRepository $userRepository)
    {
        $unwanted_array = ['Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
            'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', ];

        $firstName = strtr($firstName, $unwanted_array);
        $lastName = strtr($lastName, $unwanted_array);

        $firstName = str_replace(' ', '_', strtolower($firstName));
        $lastName = str_replace(' ', '_', strtolower($lastName));

        $login = substr($lastName, 0, 7);
        $login .= substr($firstName, 0, 8 - \strlen($login));

        //  On recherche si un utilisateur a déjà ce login
        $numberSameLogin = 0;
        while (null !== $userRepository->findOneBy(['login' => $login])) {
            ++$numberSameLogin;
            //  On enlève le dernier caractère du login
            substr_replace($login, '', -1);
            $login .= $numberSameLogin;
        }

        return $login;
    }
}
