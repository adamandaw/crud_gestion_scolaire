<?php

namespace App\DataFixtures;

use App\Entity\AnneeScolaire;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
;

class AnneeScolaireFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=2018; $i <2024 ; $i++) {
            $data=new AnneeScolaire();
            $annee= $i."-".($i+1);
            $data->setLibelle($annee)
            ->setIsActive(false);
            $manager->persist($data);
            $this->addReference("AnneeScolaire".$i, $data);
        }
        $manager->flush();
}
}