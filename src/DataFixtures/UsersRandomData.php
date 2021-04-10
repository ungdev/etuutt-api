<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserBan;
use App\Entity\UserRGPD;
use App\Entity\UserSocialNetwork;
use App\Entity\UserTimestamps;
use App\Entity\UserBDEContribution;
use App\Entity\Semester;
use App\Repository\UserRepository;
use App\Repository\SemesterRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Validator\Constraints\IsNull;

class UsersRandomData extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create("fr_FR");

        for ($i=0; $i < 100; $i++) {

            //  Créations d'un User
            $user = new User();
            $user->setStudentId(44000+$i);
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $userRepository = $manager->getRepository(User::class);
            $user->setLogin(UsersRandomData::generateLogin($user->getFirstName(), $user->getLastName(), $userRepository));

            //  On persiste l'User pour y avoir accès après
            $manager->persist($user);
            $manager->flush();


            //  Création d'un timestamps pour chaque User
            $timestamps = new UserTimestamps();
            $timestamps->setUser($user);
            $createdAt = $faker->dateTimeBetween('-3 years', 'now');
            $timestamps->setCreatedAt($createdAt);
            $days = (new DateTime())->diff($timestamps->getCreatedAt())->days;
            $timestamps->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));
            //  First and last login
            $timestamps->setFirstLoginDate($faker->dateTimeBetween('-30 days', 'now'));
            $days = (new DateTime())->diff($timestamps->getFirstLoginDate())->days;
            $timestamps->setLastLoginDate($faker->dateTimeBetween('-'.$days.' days', 'now'));
            //  Soft delete aléatoire d'un User (Avec une chance de 1%)
            if ($faker->boolean(1)) {
                $days = (new DateTime())->diff($timestamps->getLastLoginDate())->days;
                $timestamps->setDeletedAt($faker->dateTimeBetween('-'.$days.' days', 'now'));
            }

            //  On persiste le timestamps pour y avoir accès après
            $manager->persist($timestamps);
            $manager->flush();


            //  Création d'un socialNetwork pour chaque User
            $socialNetwork = new UserSocialNetwork();
            $socialNetwork->setUser($user);
            if ($faker->boolean(75)) {
                $socialNetwork->setFacebook($faker->imageUrl());
            }
            if ($faker->boolean(75)) {
                $socialNetwork->setTwitter($faker->imageUrl());
            }
            if ($faker->boolean(75)) {
                $socialNetwork->setInstagram($faker->imageUrl());
            }
            if ($faker->boolean(75)) {
                $socialNetwork->setLinkedin($faker->imageUrl());
            }
            if ($faker->boolean(75)) {
                $socialNetwork->setPseudoDiscord($faker->word);
            }
            $socialNetwork->setWantDiscordUTT($faker->boolean(75));
            $manager->persist($socialNetwork);


            //  Création d'un RGPD pour chaque User
            $RGPD = new UserRGPD();
            $RGPD->setUser($user);
            if ($faker->boolean(75)) {
                $RGPD->setIsDeletingEverything($faker->boolean(50));
            }
            if ($faker->boolean(75)) {
                $RGPD->setIsKeepingAccount($faker->boolean(50));
            }
            $manager->persist($RGPD);


            //  Ban aléatoire d'un User (Avec une chance de 1%)
            if ($faker->boolean(1)) {

                //  Création d'un objet UserBan
                $userBan = new UserBan();
                $userBan->setUser($user);

                //  Random durée ban
                $days = (new DateTime())->diff($timestamps->getLastLoginDate())->days;

                //  50% de chance de ReadOnly, 50% de Banned
                if ($faker->boolean(50)) {
                    $userBan->setReadOnlyExpiration($faker->dateTimeBetween('-'.$days.' days', '+30 days'));
                }
                else {
                    $userBan->setBannedExpiration($faker->dateTimeBetween('-'.$days.' days', '+30 days'));
                }
                //  On persiste le ban
                $manager->persist($userBan);
            }


            //  Création d'une cotisation BDE pour quelques User
            if ($faker->boolean(75)) {
                $BDEContribution = new UserBDEContribution();
                $BDEContribution->setUser($user);
                
                $BDEContribution->setStart($faker->dateTimeBetween($createdAt));

                $semesterRepository = $manager->getRepository(Semester::class);
                $contributionSemester = $semesterRepository->getSemesterOfDate($BDEContribution->getStart());

                $BDEContribution->setEnd($contributionSemester->getEnd());

                $BDEContribution->setStartSemester($contributionSemester);
                $BDEContribution->setEndSemester($contributionSemester);

                $manager->persist($BDEContribution);
            }


            //  Sauvegarde des actions précédentes en DB
            $manager->flush();
        }
    }




    /**
     * Cette fonction génère le login à partir du firstname et du lastname
     * @param String $firstName Le prénom de l'utilisateur
     * @param String $lastName Le nom de famille de l'utilisateur
     * @param UserRepository $userRepository Le repository pour accéder aux Users
     * @return String $login Le login de l'utilisateur
     */
    public static function generateLogin(String $firstName = null, String $lastName = null, UserRepository $userRepository)
    {
        
        $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );

        $firstName = strtr($firstName, $unwanted_array);
        $lastName = strtr($lastName, $unwanted_array);

        $firstName = str_replace(" ", "_", strtolower($firstName));
        $lastName = str_replace(" ", "_", strtolower($lastName));

        $login = substr($lastName, 0, 7);
        $login .= substr($firstName, 0, 8-strlen($login));

        //  On recherche si un utilisateur a déjà ce login
        $numberSameLogin = 0;
        while (!is_null($userRepository->findOneBy(['login' => $login]))) {
            $numberSameLogin++;
            //  On enlève le dernier caractère du login
            substr_replace($login ,"", -1);
            $login .= $numberSameLogin;
        }

        return $login;
    }


}
