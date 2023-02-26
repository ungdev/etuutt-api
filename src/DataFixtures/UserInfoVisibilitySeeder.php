<?php

namespace App\DataFixtures;

use App\Entity\Group;
use App\Entity\User;
use App\Entity\UserAddress;
use App\Entity\UserInfos;
use App\Entity\UserPreference;
use App\Repository\GroupRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class UserInfoVisibilitySeeder extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            UserSeeder::class,
            GroupSeeder::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $users = $manager->getRepository(User::class)->findAll();
        $groupRepo = $manager->getRepository(Group::class);

        foreach ($users as $user) {
            //  On ajoute une entité UserPreference
            $preference = $user->getPreference();
            $preference->setBirthdayDisplayOnlyAge($faker->boolean());
            $preference->setLanguage($faker->randomElement(['fr', 'en', 'es', 'de', 'zh']));
            $preference->setWantDaymail($faker->boolean());
            $preference->setWantDayNotif($faker->boolean());
            $preference->setBirthdayDisplayOnlyAge($faker->boolean());
            //  Ajout de visibilité pour l'emploi du temps
            $this->setFieldVisibility($preference, 'addScheduleVisibility', $faker, $groupRepo);

            //  On ajoute une entité UserInfos
            $infos = $user->getInfos();
            $infos->setSex($faker->randomElement(['Masculin', 'Féminin', 'Autre']));
            $this->setFieldVisibility($infos, 'addSexVisibility', $faker, $groupRepo);
            $infos->setNationality($faker->countryCode);
            $this->setFieldVisibility($infos, 'addNationalityVisibility', $faker, $groupRepo);
            $infos->setBirthday($faker->dateTimeBetween('-25 years', '-20 years'));
            $this->setFieldVisibility($infos, 'addBirthdayVisibility', $faker, $groupRepo);
            $infos->setAvatar($faker->imageUrl());
            if ($faker->boolean(80)) {
                $infos->setNickname($faker->word);
                $infos->setPassions($faker->word.' '.$faker->word.' '.$faker->word);
                $infos->setWebsite($faker->imageUrl());
            }

            //  On ajoute de 0 à 2 addresses pour l'utilisateur
            for ($i = 0; $i < $faker->numberBetween(0, 2); ++$i) {
                //  Les utilisateurs ont par défaut une addresse. Si il y en a plus, il faut la créer.
                if (0 === $i) {
                    $address = $user->getAddresses()[0];
                } else {
                    $address = new UserAddress();
                    $user->addAddress($address);
                }
                $address->setStreet($faker->streetAddress);
                $address->setPostalCode($faker->postcode);
                $address->setCity($faker->city);
                $address->setCountry($faker->country);
                $this->setFieldVisibility($address, 'addAddressVisibility', $faker, $groupRepo);
            }

            //  On ajoute une entité UserMailsPhones
            $mailPhone = $user->getMailsPhones();
            $mailPhone->setMailPersonal($faker->email);
            $mailUTT = $faker->email;
            $mailPhone->setMailUTT(substr($mailUTT, 0, strpos($mailUTT, '@')).'@utt.fr');
            $mailPhone->setPhoneNumber($faker->phoneNumber);
            $this->setFieldVisibility($mailPhone, 'addMailPersonalVisibility', $faker, $groupRepo);
            $this->setFieldVisibility($mailPhone, 'addPhoneNumberVisibility', $faker, $groupRepo);
        }

        $manager->flush();
    }

    /**
     * Cette fonction attribut des groupes de visibilité avec le setter passé en paramètre.
     *
     * @param object          $entity     L'entité dont on va appeler une méthode
     * @param string          $methodName Le adder du champ dont on va modifier la visibilité
     * @param Generator       $faker      La factory faker pour générer des données
     * @param GroupRepository $groupRepo  Le repository pour accéder aux Users
     */
    public static function setFieldVisibility(object $entity, string $methodName, Generator $faker, GroupRepository $groupRepo)
    {
        $groups = $groupRepo->findAll();
        $groupChoice = $faker->numberBetween(0, 2);

        switch ($groupChoice) {
            case 0:
                //  Private, data shared with no groups.

                break;

            case 1:
                $group = $groupRepo->findOneBy(['name' => 'Public']);
                $entity->caller($methodName, $group);

                break;

            case 2:
                for ($i = 0; $i < $faker->numberBetween(1, 6); ++$i) {
                    $group = $faker->randomElement($groups);
                    $entity->caller($methodName, $group);
                }

                break;
        }
    }
}
