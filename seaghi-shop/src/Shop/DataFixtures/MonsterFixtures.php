<?php

namespace App\Shop\DataFixtures;

use App\Shop\Entity\Category;
use App\Shop\Entity\Monster;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MonsterFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categoryRepository = $manager->getRepository(Category::class);
        /** @var Category $wildSquirrel */
        $wildSquirrel = $categoryRepository->findOneBy(['code' => 'wild_squirrel']);
        /** @var Category $shapeshifterChicken */
        $shapeshifterChicken = $categoryRepository->findOneBy(['code' => 'shapeshifter_chicken']);
        /** @var Category $lolCat */
        $lolCat = $categoryRepository->findOneBy(['code' => 'lol_cat']);
        /** @var Category $caribouAvenger */
        $caribouAvenger = $categoryRepository->findOneBy(['code' => 'caribou_avenger']);

        $monstersData = [
            [$wildSquirrel, 2, 20, 'Eater', 'Acorn', true, false],
            [$wildSquirrel, 4, 30, 'Climber', 'Tree', true, false],
            [$shapeshifterChicken, 2, 50, 'Isaw', 'Aunicorn', true, false],
            [$shapeshifterChicken, 5, 120, 'Big', 'Brain', true, false],
            [$lolCat, 3, 75, 'Master', 'Oftheworld', true, false],
            [$caribouAvenger, 3, 240, 'Frosted', 'Walker', true, false],
            [$caribouAvenger, 12, 1340, 'Fog', 'Horn', true, false],
        ];

        foreach ($monstersData as [$category, $level, $price, $firstName, $lastName, $available, $sick]) {
            $monster = new Monster();
            $monster->setCategory($category);
            $monster->setLevel($level);
            $monster->setPrice($price);
            $monster->setFirstName($firstName);
            $monster->setLastName($lastName);
            $monster->setAvailable($available);
            $monster->setSick($sick);
            $manager->persist($monster);
        }

        $manager->flush();
    }
}
