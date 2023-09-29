<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private const NB_EMPLOYEES = 100;
    private const CONTRACT_TYPES = ["CDI", "CDD", "Intérim"];
    private const SECTOR = ["RH", "Informatique", "Comptabilité", "Direction"];
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $admin = new User();
        $admin
            ->setEmail('rh@hb.com')
            ->setPassword(password_hash('azerty123', PASSWORD_BCRYPT))
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER'])
            ->setFirstname($faker->firstName())
            ->setLastname($faker->lastName())
            ->setPicture($faker->imageUrl())
            ->setSector($faker->randomElement(self::SECTOR))
            ->setContract($faker->randomElement(self::CONTRACT_TYPES))
            ->setDateOfEnd($faker->dateTimeBetween('now', '+5 years'));

        $manager->persist($admin);

        $employee = new User();
        $employee
            ->setEmail('employee@hb.com')
            ->setPassword(password_hash('azerty123', PASSWORD_BCRYPT))
            ->setRoles(['ROLE_USER'])
            ->setFirstname($faker->firstName())
            ->setLastname($faker->lastName())
            ->setPicture($faker->imageUrl())
            ->setSector($faker->randomElement(self::SECTOR))
            ->setContract($faker->randomElement(self::CONTRACT_TYPES))
            ->setDateOfEnd($faker->dateTimeBetween('now', '+5 years'));
            
        $manager->persist($employee);

        for ($i = 0; $i < self::NB_EMPLOYEES; $i++)
        {
            $user = new User();
            $user
                ->setEmail($faker->email())
                ->setPassword(password_hash($faker->password(), PASSWORD_BCRYPT))
                ->setRoles(['ROLE_USER'])
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setPicture($faker->imageUrl())
                ->setSector($faker->randomElement(self::SECTOR))
                ->setContract($faker->randomElement(self::CONTRACT_TYPES))
                ->setDateOfEnd($faker->dateTimeBetween('now', '+5 years'));
            
            $manager->persist($user);
        }

        $manager->flush();
    }
}
