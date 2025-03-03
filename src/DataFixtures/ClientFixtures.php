<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Client;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $client = new Client();
        $client->setFirstname("Lucas");
        $client->setLastname("Jordan");
        $client->setEmail("lucas.jordan@kub.fr");
        $client->setPhoneNumber("0606060606");
        $client->setAddress("1 rue de la rue, Villebourg");

        $manager->persist($client);

        $client = new Client();
        $client->setFirstname("Olivier");
        $client->setLastname("Müller");
        $client->setEmail("o.muller@gmail.com");
        $client->setPhoneNumber("0707070707");
        $client->setAddress("241 boulevard de l'Europe, Mulhouse");

        $manager->persist($client);

        $client = new Client();
        $client->setFirstname("Benjamin");
        $client->setLastname("Arnaud");
        $client->setEmail("benji@mail.ch");
        $client->setPhoneNumber("0607060706");
        $client->setAddress("12 rue de l'innomable, Donhom");

        $manager->persist($client);

        $client = new Client();
        $client->setFirstname("Sylvain");
        $client->setLastname("Levy");
        $client->setEmail("sylv@vroum.com");
        $client->setPhoneNumber("0606070606");
        $client->setAddress("4 rue de Flash McQueen, Radiator Spring");

        $manager->persist($client);

        $client = new Client();
        $client->setFirstname("Simon");
        $client->setLastname("Golf");
        $client->setEmail("simonG@gmail.com");
        $client->setPhoneNumber("0675677221");
        $client->setAddress("9 rue de Gransax, Leyndell");

        $manager->persist($client);

        $manager->flush();
    }
}
