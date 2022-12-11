<?php

namespace App\DataFixtures;

use App\Entity\Users; 
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UsersFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new Users();
       
            $user->setemail('daoudasouleymanecamara8@gmail.com')
            ->setFirstname('Daouda')
            ->setLastname('CAMARA')
            ->setPassword('daouda326832')
            ->setAdress('76 Rue du Repos')
            ->setZipcode(69007)
            ->setCity('Lyon')
            
            ;
       
        $manager->persist($user);
        // 2
        $user = new Users();
       
            $user->setemail('daoudacamara8@gmail.com')
            ->setFirstname('Daoud')
            ->setLastname('CAMAR')
            ->setPassword('daouda32632')
            ->setAdress('76 Rue du Rep')
            ->setZipcode(69107)
            ->setCity('Lyon')
            
            ;
       
        $manager->persist($user);

        $manager->flush();
    }
}
