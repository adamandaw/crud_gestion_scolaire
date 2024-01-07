<?php

namespace App\DataFixtures;

use App\Entity\Participation;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
;

class ParticipationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i <= 4; $i++) { 
            $participation= new Participation();
            $participation->setCour($this->getReference("Cours". $i));
            $participation->setClasse($this->getReference("Classe". $i));
  
           
            $manager->persist($participation);
            $this->addReference("Participation".$i,$participation);

        }
        $manager->flush();
    }
    public function getDependencies(){
        return array(
            CoursFixtures::class,
            ClasseFixtures::class,
        ); 
       }
}
