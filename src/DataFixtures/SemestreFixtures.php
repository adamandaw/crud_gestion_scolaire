<?php

namespace App\DataFixtures;

use App\Entity\Semestre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class SemestreFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
       
        $argsSemestres=['Semestre 1','Semestre 2','Semestre 3','Semestre 4','Semestre 5','Semestre 6'];
        for ($i=1; $i <= 6 ; $i++) { 
            $sem = new Semestre();
            $pos =rand(0,5);
            $sem->setLibelle($argsSemestres[$pos]);
             $manager->persist($sem);
             $this->addReference("Semestre".$i,$sem);
        }
        $manager->flush();
    }
}
