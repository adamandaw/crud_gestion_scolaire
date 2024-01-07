<?php

namespace App\DataFixtures;

use App\Entity\Module;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
;

class ModuleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $modules=['Flutter','Java','Angular','Sql Server','TypeScript','Marketing Digitale',
                    'Mathematique',"Cisco","Application Mobile","Design Sonore","Gestion des risques","Machine Learning",
                "Laravel","Docker","C#","Securite Informatique","C++"];
        for ($i=1; $i < count($modules) -1; $i++) { 
            $module= new Module();
            $module->setLibelle($modules[$i]);
  
            $module->setIsArchived(false);
            $this->addReference("Module".$i,$module);
            $manager->persist($module);
        }
        
        $manager->flush();
    }
}
