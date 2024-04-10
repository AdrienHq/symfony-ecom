<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public const ADMIN = 'ADMIN_USER';
    public const USER = 'USER';
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    )
    {
    }


    public function load(ObjectManager $manager): void
    {
        $userAdmin = new User();
        $userAdmin->setRoles(['ROLE_ADMIN'])
            ->setEmail('admin@email.com')
            ->setUsername('admin')
            ->setPassword($this->hasher->hashPassword($userAdmin, 'admin'))
            ->setIsVerified(true)
            ->setBirthdayDate(new \DateTimeImmutable());
        $this->addReference(self::ADMIN, $userAdmin);

        $manager->persist($userAdmin);

        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setRoles(['ROLE_ADMIN'])
                ->setEmail('user{$i}@email.com')
                ->setUsername('user{$i}')
                ->setPassword($this->hasher->hashPassword($user, 'user'))
                ->setIsVerified(true)
                ->setBirthdayDate(new \DateTimeImmutable());

            $manager->persist($user);
            $this->addReference(self::USER . $i, $user);
        }

        $manager->flush();
    }
}
