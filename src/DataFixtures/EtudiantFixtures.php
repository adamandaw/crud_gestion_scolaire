<?php

namespace App\DataFixtures;

use App\Entity\Etudiant;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
;

class EtudiantFixtures extends Fixture
{

    private $encoder;
    // private Generator $faker;
    public function __construct(UserPasswordHasherInterface $encoder){
                $this->encoder=$encoder;
                // $this->faker = Factory::create();
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();


        $sexes=["M","F"];
        $nationalites=["Sénégal","Mali","Burkina-Faso","Guinnée","Togo","Côte-D'ivoire","Ghana","Tchad","Congo","Gabon","Afrique du Sud","Kenya","Maroc","Tunisie"];

        $plainPassword = 'passer@123';
        for ($i = 1; $i <=10; $i++) {
            $user = new Etudiant();
            $mat="ISM_".uniqid();
            $nomComplets=$faker->firstName()." ".$faker->lastName();
            $posSexe=rand(0,1);
            $posNationalite=rand(0,13);
            $user->setNomComplet($nomComplets);
            $user->setEmail(str_replace(' ','',strtolower($nomComplets).'@gmail.com'));
            $encoded = $this->encoder->hashPassword($user,$plainPassword);
            $user->setPassword($encoded);
            $user->setTuteur($faker->firstName());
             $user->setMatricule($mat.$i);
            $date = new \DateTimeImmutable($faker->date('Y-m-d'));
            $newDate= $date->add(new \DateInterval('P10D'));
            $user->setDateDeNaissanceAt($newDate);
            $user->setSexe($sexes[$posSexe]);
            $user->setLieuDeNaissance($nationalites[$posNationalite]);
            
            $manager->persist($user);
            $this->addReference("Etudiant".$i, $user);
        }
        $manager->flush();
    }
}
