<?php

namespace App\DataFixtures;

use App\Entity\Categories; 
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriesFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger){
        // 
    }
    public function load(ObjectManager $manager): void
    {
        $parent = new Categories();
        $parent->setName('Informatique 4.0');
        $parent->setSlug($this->slugger->slug(($parent->getName()))->lower());
        
        $manager->persist($parent);
        $category = new Categories();
        $category->setName('Ordinateur portables');
        $category->setSlug($this->slugger->slug(($category->getName()))->lower());
        $category->setParent($parent);

        $manager->persist($category);

        $manager->flush();
    }
}
