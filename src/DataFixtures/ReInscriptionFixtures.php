<?php

namespace App\DataFixtures;

use App\DataFixtures\ClasseFixtures;
use App\DataFixtures\EtudiantFixtures;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\AnneeScolaireFixtures;
use App\Entity\ReInscription;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
;

class ReInscriptionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i=1; $i <=10 ; $i++) { 
            $annee=rand(2019,2020);
            $data = new ReInscription();    
            $data->setClasse($this->getReference("Classe".$i));
            $data->setEtudiant($this->getReference("Etudiant".$i));
            $data->setAnneeScolaire($this->getReference("AnneeScolaire".$annee));
            $data->setMontant(mt_rand(1, 40) * 10000);
            $manager->persist($data);
            $this->addReference("ReInscription".$i, $data);
          }

        $manager->flush();
    }

    public function getDependencies(){
        return array(
            EtudiantFixtures::class,
            ClasseFixtures::class,
            AnneeScolaireFixtures::class
        ); 
       }
}
