<?php

namespace App\DataFixtures;

use App\Entity\Users; 
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UsersFixtures extends Fixture
{
    public function __construct(private  UserPasswordHasherInterface $passwordEncoder, private SluggerInterface $lugger )
    {

    }
    public function load(ObjectManager $manager): void
    {
        $admin = new Users();
       
            $admin->setemail('daoudasouleymanecamara8@gmail.com')
            ->setFirstname('Daouda')
            ->setLastname('CAMARA')
            // hash du password 
            ->setPassword(
                $this->passwordEncoder->hashPassword($admin, 'daouda326832')
            )
            ->setAdress('76 Rue du Repos')
            ->setZipcode(69007)
            ->setCity('Lyon')
            ->setRoles(['ROLE_ADMIN'])
            
            ;
       
        $manager->persist($admin);
    
        $manager->flush();
    }
}
