<?php

declare(strict_types=1);

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
            [$wildSquirrel, 2, 20, 'Eater', 'Acorn'],
            [$wildSquirrel, 4, 30, 'Climber', 'Tree'],
            [$shapeshifterChicken, 2, 50, 'Isaw', 'Aunicorn'],
            [$shapeshifterChicken, 5, 120, 'Big', 'Brain'],
            [$lolCat, 3, 75, 'Master', 'Oftheworld'],
            [$caribouAvenger, 3, 240, 'Frosted', 'Walker'],
            [$caribouAvenger, 12, 1340, 'Fog', 'Horn'],
        ];

        foreach ($monstersData as [$category, $level, $price, $firstName, $lastName]) {
            $monster = new Monster(
                $category,
                $level,
                $price,
                $firstName,
                $lastName,
            );
            $manager->persist($monster);
        }

        $manager->flush();
    }
}
