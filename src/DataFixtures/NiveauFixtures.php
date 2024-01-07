<?php

namespace App\DataFixtures;

use App\Entity\Niveau;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class NiveauFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $niveaux=["L1","L2","L3"];
        for ($i=0; $i < count($niveaux) ; $i++) { 
            $data= new Niveau();
            $data->setLibelle($niveaux[$i]);
            $data->setIsArchived(false);
            $manager->persist($data);
            $this->addReference("Niveau".$i,$data);
        }


        $manager->flush();
    }
}
