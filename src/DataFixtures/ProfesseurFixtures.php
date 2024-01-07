<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Professeur;
use App\DataFixtures\ClasseFixtures;
use App\DataFixtures\ModuleFixtures;
use App\Repository\ClasseRepository;
use App\Repository\ModuleRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
;

class ProfesseurFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;
    private ModuleRepository $moduleRepository;
    private ClasseRepository $classeRepository;
    public function __construct( ModuleRepository $moduleRepository,ClasseRepository $classeRepository,UserPasswordHasherInterface $encoder)
    {
        $this->encoder=$encoder;
       $this->moduleRepository=$moduleRepository;
       $this->classeRepository=$classeRepository;

    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $grades=["MASTER","INGENIEUR","DOCTORAT"];
        $plainPassword = 'passer';
        $profs=[];
        
        for ($i = 1; $i <=10; $i++) {
            $posGrade=rand(0,2);
            $nomComplets=$faker->firstName()." ".$faker->lastName();
            $prof = new Professeur();
            $this->addReference("Professeur" . $i, $prof);
            $prof->setNomComplet($nomComplets);
            $prof->setEmail(str_replace(' ','',strtolower($nomComplets).'@gmail.com'));
            $prof->setGrade($grades[$posGrade]);
            // $prof->;
            $encoded = $this->encoder->hashPassword($prof,$plainPassword);
            
            $prof->setPassword($encoded);

            
            $manager->persist($prof);
            $profs[]=$prof;
        }
        $module= $this->moduleRepository->findAll();
        $classes= $this->classeRepository->findAll();
        for ($j = 0; $j < count($module); $j++) {
            $mod = $module[$j];
            for ($i = 0; $i < rand(1, 4); $i++) {
                $mod->addProfesseur($profs[mt_rand(0, count($profs) - 1)]);
            }
        }
        for ($z = 0; $z < count($classes); $z++) {
            $cl = $classes[$z];
            for ($o = 0; $o < rand(1, 4); $o++) {
                $cl->addProfesseur($profs[mt_rand(0, count($profs) - 1)]);
            }
        }
        $manager->flush();
        

    }
    public function getDependencies(){
        return array(
            ModuleFixtures::class,
            // ClasseFixtures::class
        ); 
    }
    
}
