<?php

namespace App\DataFixtures;


// use Faker;
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
        // $parent = new Categories();
        // $parent->setName('Informatique 4.0');
        $parent = $this->createCategory('Informatique' ,null, $manager );
        // $parent = $this->createCategory(name : 'Informatique' ,manager: $manager );
        // $parent->setSlug($this->slugger->slug(($parent->getName()))->lower());
        
        // $manager->persist($parent);

        // deuxième categories 

        // $category = new Categories();
        // $category->setName('Ordinateur portables');

        $category = $this->createCategory('Ordinateur Portable', $parent, $manager);
        $category = $this->createCategory('Ecrans', $parent, $manager);
        $category = $this->createCategory('Souris', $parent, $manager);
        $category = $this->createCategory('Chargeurs', $parent, $manager);
        $category = $this->createCategory('PC fixes', $parent, $manager);
      
        // $category->setSlug($this->slugger->slug(($category->getName()))->lower());
        // $category->setParent($parent);

        // Modes 
        $parent = $this->createCategory('Modes' ,null, $manager );

        $category = $this->createCategory('Hommes', $parent, $manager);
        $category = $this->createCategory('Femmes', $parent, $manager);
        $category = $this->createCategory('Enfants', $parent, $manager);
       

        $manager->persist($category);

       

        $manager->flush();
    }
    public function createCategory(string $name, Categories $parent = null , ObjectManager $manager){

        $category = new Categories();
        // $category->setName('Ordinateur portables');
        $category->setName($name);
        $category->setSlug($this->slugger->slug(($category->getName()))->lower());
        $category->setParent($parent);
        $manager->persist($category);

        return $category;

    }
}
