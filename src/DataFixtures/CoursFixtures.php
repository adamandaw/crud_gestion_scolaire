<?php

namespace App\DataFixtures;

use App\Entity\Cours;
use App\DataFixtures\ModuleFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
;

class CoursFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
       
        for ($i=1; $i <= 4; $i++) { 
            $cour= new Cours();
            $n = rand(13,24);
            $cour->setNbrHeureGlobal($n);
            $cour->setNbrHeureEffectuer($n);
            $cour->setNbrHeurePlanifier($n);

            $cour->setModule($this->getReference("Module".$i));
            $cour->setProfesseur($this->getReference("Professeur".$i));
            
            $cour->setAnneeScolaire($this->getReference("AnneeScolaire".($i + 2018)));
            $cour->setSemestre($this->getReference("Semestre".$i));
            
           
            $manager->persist($cour);
            $this->addReference("Cours".$i,$cour);

        }
        $manager->flush();
    }
    public function getDependencies(){
        return array(
            ProfesseurFixtures::class,
            ModuleFixtures::class,
            AnneeScolaireFixtures::class,
            SemestreFixtures::class
        ); 
       }
}
