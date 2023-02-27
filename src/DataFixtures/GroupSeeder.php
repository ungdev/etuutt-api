<?php

namespace App\DataFixtures;

use App\Entity\Group;
use App\Entity\User;
use App\Util\Slug;
use App\Util\Text;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class GroupSeeder extends Fixture implements DependentFixtureInterface
{
    /**
     * @var int The minimum number of groups which will have the isVisible property set to true
     */
    protected int $minimumVisibleGroupCount;
    /**
     * @var int The maximum number of groups which will have the isVisible property set to true
     */
    protected int $maximumVisibleGroupCount;

    public function __construct(int $minimumVisibleGroupCount = 0, int $maximumVisibleGroupCount = 20)
    {
        $this->minimumVisibleGroupCount = $minimumVisibleGroupCount;
        $this->maximumVisibleGroupCount = $maximumVisibleGroupCount;
    }

    public function getDependencies()
    {
        return [
            UserSeeder::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $users = $manager->getRepository(User::class)->findAll();

        // How many groups which have the property isVisible set to true have been created so far
        $visibleGroupCount = 0;

        //  Création de 20 groupes
        for ($i = 0; $i < 20; ++$i) {
            //  Créations d'un group
            $group = new Group();

            $name = match ($i) {
                0 => 'Public',
                default => implode(' ', $faker->words),
            };
            $group->setName($name);
            $group->setSlug(Slug::slugify($name));

            // isVisible property. We might need to force the last groups to be visible or not, so that the minimum/maximum amounts are respected.
            if ($visibleGroupCount < $this->minimumVisibleGroupCount) {
                $isVisible = true;
            } else if ($visibleGroupCount < $this->maximumVisibleGroupCount) {
                $isVisible = $faker->boolean(75);
            } else {
                $isVisible = false;
            }
            
            if ($isVisible) {
                $visibleGroupCount++;
            }
            $group->setIsVisible($isVisible);

            //  Création d'une traduction
            $descriptionTranslation = $group->getDescriptionTranslation();

            $description = Text::createRandomText(5, 9);
            $descriptionTranslation->setFrench($description);
            $descriptionTranslation->setEnglish($description);
            $descriptionTranslation->setSpanish($description);
            $descriptionTranslation->setGerman($description);
            $descriptionTranslation->setChinese($description);

            $manager->persist($descriptionTranslation);

            //  Ajout d'avatar
            $group->setAvatar($faker->imageUrl());

            //  Création des timestamps
            $group->setCreatedAt($faker->dateTimeBetween('-5 years'));
            $days = (new \DateTime())->diff($group->getCreatedAt())->days;
            $group->setUpdatedAt($faker->dateTimeBetween('-'.$days.' days'));
            //  Soft delete aléatoire d'un User (Avec une chance de 1%)
            if ($faker->boolean(10)) {
                $days = (new \DateTime())->diff($group->getUpdatedAt())->days;
                $group->setDeletedAt($faker->dateTimeBetween('-'.$days.' days'));
            }

            //  On persiste l'entité dans la base de données
            $manager->persist($group);
        }
        $manager->flush();

        //  Attribution de groupes aux utilisateurs
        //  Récupération des groups
        $groups = $manager->getRepository(Group::class)->findAll();

        foreach ($groups as $group) {
            if ('Privé' !== $group->getName()) {
                foreach ($users as $user) {
                    if ($faker->boolean(15)) {
                        $group->addMember($user);
                    }
                    if ($faker->boolean(2)) {
                        $group->addAdmin($user);
                    }
                }
            }
        }
        $manager->flush();
    }
}
