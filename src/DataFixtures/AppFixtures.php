<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Listing;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $categ1 = new Category;
        $categ1->setTitle('Art');
        $manager->persist($categ1);

        $categ2 = new Category;
        $categ2->setTitle('Furniture');
        $manager->persist($categ2);

        $categ3 = new Category;
        $categ3->setTitle('Clothing');
        $manager->persist($categ3);

        $categ4 = new Category;
        $categ4->setTitle('Other');
        $manager->persist($categ4);


        $this->addReference('category_1', $categ1);
        $this->addReference('category_2', $categ2);
        $this->addReference('category_3', $categ3);
        $this->addReference('category_4', $categ4);


        for ($i = 0; $i <= 10; $i++) {
            $listing = new Listing();
            $listing
                ->setTitle(ucfirst($faker->word))
                ->setDescription($faker->realText($maxNbChars = 200))
                ->setPrice($faker->numberBetween(100, 5000))
                ->setCategory($this->getReference('category_'.$faker->numberBetween(1, 4)))
                ->setCreatedAt($faker->dateTimeThisMonth());

            $manager->persist($listing);
            
        }

        $manager->flush();
    }
}
