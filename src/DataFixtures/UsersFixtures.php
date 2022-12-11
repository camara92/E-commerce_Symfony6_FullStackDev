<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Users;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UsersFixtures extends Fixture
{
    public function __construct(private  UserPasswordHasherInterface $passwordEncoder, private SluggerInterface $lugger)
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
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $faker = Faker\Factory::create('fr_FR');
        for ($usr = 1; $usr < 10; $usr++) {
            $user = new Users();

            $user->setEmail($faker->email())
                ->setFirstname($faker->FirstName())
                ->setLastname($faker->lastName())
                // hash du password 
                ->setPassword(
                    $this->passwordEncoder->hashPassword($user, $faker->password())
                )
                ->setAdress($faker->streetAddress())
                ->setZipcode(str_replace(" ", "", $faker->postcode(5)))
                ->setCity($faker->city())
                // ->setRoles(['ROLE_USER'])
            ;

            $manager->persist($user);
        }

        $manager->flush();
    }
}
