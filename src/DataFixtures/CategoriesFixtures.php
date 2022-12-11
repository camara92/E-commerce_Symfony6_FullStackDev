<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Categories; 
class CategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $parent = new Categories();
        $parent->setName('Informatique');
        $parent->setSlug('informatique');
        
        $manager->persist($parent);
        $category = new Categories();
        $category->setName('Ordinateur');
        $category->setSlug('pcpcpce');
        $category->setParent($parent);

        $manager->persist($category);

        $manager->flush();
    }
}
