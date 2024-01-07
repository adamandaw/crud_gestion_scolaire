<?php

namespace App\DataFixtures;

use App\Entity\Filiere;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class FiliereFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $datas=["CDSD","MAIE","GLRS","IAGE"];

        for ($i=0; $i <count($datas) ; $i++) { 
              $data = new Filiere();
              $data->setLibelle($datas[$i]);
              $data->setIsArchived(false);
              $manager->persist($data);//insertion
              $this->addReference("Filiere".$i,$data);
        }
        $manager->flush();
    }
}
