<?php

namespace App\DataFixtures;
use Faker;
use App\Entity\Products; 
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductsFixtures extends Fixture 
{
     
    public function __construct(private SluggerInterface $slugger )
    {
        
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($produit=1; $produit <=10 ; $produit++) { 
            $product = new Products();
            $product->setName($faker ->text(15));
            $product->setDescription($faker ->text());
            $product->setSlug($this->slugger->slug($product->getName())->lower());
            $product->setPrice($faker ->numberBetween(600, 150000));
            $product->setStock($faker ->numberBetween(0, 100));

            // cherhcer une référence de catégorie
            
            $category= $this->getReference('cat-'.rand(2,3));
            $product->setCategories($category);
            
         $manager->persist($product);
         $this->addReference('prod-'.$produit, $product);

        }

        $manager->flush();
    }
    
}
