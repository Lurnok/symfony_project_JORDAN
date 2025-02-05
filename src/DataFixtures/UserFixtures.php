<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\RolesEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $userReference = "USER";
    private $userPasswordHasherInterface;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }
    public function load(ObjectManager $manager): void
    {


        $user = new User();
        $user->setEmail("lucas.jordan@admin.fr");
        $user->setFirstname("Lucas");
        $user->setLastname("Jordan");
        $user->setRoles([RolesEnum::admin]);
        $user->setPassword($this->userPasswordHasherInterface->hashPassword($user,"adminpass"));
        $this->addReference($this->userReference  . "-01",$user);
        $manager->persist($user);

        $user = new User();
        $user->setEmail("lirasu@gmail.com");
        $user->setFirstname("Alexandre");
        $user->setLastname("Melin");
        $user->setRoles([RolesEnum::manager]);
        $user->setPassword($this->userPasswordHasherInterface->hashPassword($user,"managerpass"));
        $this->addReference($this->userReference  . "-02",$user);
        $manager->persist($user);

        $user = new User();
        $user->setEmail("manumacs@gmail.com");
        $user->setFirstname("Emmanuel");
        $user->setLastname("Macron");
        $user->setRoles([RolesEnum::user]);
        $user->setPassword($this->userPasswordHasherInterface->hashPassword($user,"userpass"));
        $this->addReference($this->userReference  . "-03",$user);
        $manager->persist($user);

        $manager->flush();
    }
}
