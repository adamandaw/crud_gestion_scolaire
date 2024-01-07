<?php

namespace App\DataFixtures;

use App\Entity\Classe;
use App\Entity\Module;
use App\DataFixtures\ModuleFixtures;
use App\Repository\ClasseRepository;
use App\Repository\ModuleRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
;

class ClasseFixtures extends Fixture implements DependentFixtureInterface
{
    private ClasseRepository $classeRepository;
    private ModuleRepository $moduleRepository;
    public function __construct(ClasseRepository $classeRepository, ModuleRepository $moduleRepository)
    {
       $this->classeRepository=$classeRepository;
       $this->moduleRepository=$moduleRepository;
    }
    public function load(ObjectManager $manager): void
    {
      $tabClasses=[];
        for ($i=1; $i <=10 ; $i++) { 
            $data = new Classe();    
            $refNiveau=rand(0,2);
            $refFiliere=rand(0,3);     
           $niveau=$this->getReference("Niveau". $refNiveau);//Objet
          $filiere=$this->getReference("Filiere". $refFiliere);//Objet
           $nomClasse=$niveau->getLibelle()." ".$filiere->getLibelle();
            $result=$this->classeRepository->findOneBy(["libelle"=> $nomClasse]);
          if($result==null){
            $data->setLibelle($nomClasse);
            $data->setFiliere($filiere);
            $data->setNiveau($niveau);
            $data->setIsArchived(false);
            $manager->persist($data);//insertion
            $this->addReference("Classe".$i, $data);
            $tabClasses[]=$data;
           }
          }
          $module= $this->moduleRepository->findAll();
          for ($j = 0; $j < count($module); $j++) {
            $mod = $module[$j];
              for ($i = 0; $i < rand(1, 4); $i++) {
                  $mod->addClass($tabClasses[mt_rand(0, count($tabClasses) - 1)]);
              }
          }

          
          $manager->flush();

        
    }
    public function getDependencies(){
        return array(
            FiliereFixtures::class,
            NiveauFixtures::class,
            ModuleFixtures::class
        ); 
       }
}
